/**
 * RPD WhatsApp OTP Gateway
 * ------------------------------------------------------------------
 * Gateway WhatsApp berbasis Baileys (pairing via QR, seperti WhatsApp Web).
 * Tugasnya: menerima request HTTP dari aplikasi Laravel lalu mengirim pesan
 * (kode OTP) ke nomor WhatsApp tujuan.
 *
 * Endpoint:
 *   GET  /health           -> status koneksi
 *   POST /send             -> kirim pesan { number, message }  (Bearer token)
 *
 * Cocok dengan App\Services\WhatsApp\HttpWhatsAppProvider di Laravel:
 *   - dikirim sebagai JSON { sender, number, target, message }
 *   - autentikasi via header Authorization: Bearer <API_TOKEN>
 * ------------------------------------------------------------------
 */

'use strict';

require('dotenv').config();

const express = require('express');
const P = require('pino');
const qrcode = require('qrcode-terminal');
const {
  default: makeWASocket,
  useMultiFileAuthState,
  fetchLatestBaileysVersion,
  DisconnectReason,
} = require('@whiskeysockets/baileys');

const PORT = parseInt(process.env.PORT || '3000', 10);
const HOST = process.env.HOST || '127.0.0.1';
const API_TOKEN = process.env.API_TOKEN || '';
const AUTH_DIR = process.env.AUTH_DIR || './auth';

let sock = null;
let connected = false;
let reconnecting = false;

/**
 * Membuka koneksi ke WhatsApp dan menangani QR / reconnect.
 */
async function connectWA() {
  const { state, saveCreds } = await useMultiFileAuthState(AUTH_DIR);
  const { version } = await fetchLatestBaileysVersion();

  sock = makeWASocket({
    version,
    auth: state,
    logger: P({ level: 'silent' }),
    browser: ['RPD-OTP', 'Chrome', '1.0.0'],
  });

  sock.ev.on('creds.update', saveCreds);

  sock.ev.on('connection.update', (update) => {
    const { connection, lastDisconnect, qr } = update;

    if (qr) {
      console.log('\n==================================================');
      console.log(' SCAN QR DI BAWAH INI:');
      console.log(' WhatsApp > Setelan > Perangkat Tertaut > Tautkan Perangkat');
      console.log('==================================================\n');
      qrcode.generate(qr, { small: true });
    }

    if (connection === 'open') {
      connected = true;
      reconnecting = false;
      console.log('[WA] Tersambung & siap mengirim pesan. ✅');
    }

    if (connection === 'close') {
      connected = false;
      const statusCode = lastDisconnect && lastDisconnect.error
        ? (lastDisconnect.error.output ? lastDisconnect.error.output.statusCode : undefined)
        : undefined;
      const loggedOut = statusCode === DisconnectReason.loggedOut;

      console.log(`[WA] Koneksi terputus (code: ${statusCode}). LoggedOut: ${loggedOut}`);

      if (loggedOut) {
        console.log('[WA] Sesi logout. Hapus folder auth lalu restart untuk scan QR ulang:');
        console.log(`     rm -rf ${AUTH_DIR} && pm2 restart rpd-wa-gateway`);
      } else if (!reconnecting) {
        reconnecting = true;
        console.log('[WA] Mencoba menyambung ulang dalam 3 detik...');
        setTimeout(() => connectWA().catch((e) => console.error('[WA] reconnect error:', e.message)), 3000);
      }
    }
  });
}

/**
 * Normalisasi nomor: buang non-digit, ubah awalan 0 -> 62 (Indonesia).
 */
function normalizeNumber(num) {
  let n = String(num).replace(/\D/g, '');
  if (n.startsWith('0')) {
    n = '62' + n.slice(1);
  }
  return n;
}

// ----------------------------- HTTP API -----------------------------
const app = express();
app.use(express.json());

function requireAuth(req, res, next) {
  if (!API_TOKEN) return next(); // token tidak diset = bebas (tidak disarankan untuk produksi)
  const header = req.headers.authorization || '';
  const bearer = header.startsWith('Bearer ') ? header.slice(7) : null;
  const token = bearer || req.body.token || req.query.token;
  if (token !== API_TOKEN) {
    return res.status(401).json({ ok: false, error: 'unauthorized' });
  }
  return next();
}

app.get('/health', (req, res) => {
  res.json({ ok: true, connected });
});

app.post('/send', requireAuth, async (req, res) => {
  try {
    const number = req.body.number || req.body.target || req.body.to;
    const message = req.body.message || req.body.text;

    if (!number || !message) {
      return res.status(422).json({ ok: false, error: 'number & message wajib diisi' });
    }
    if (!connected || !sock) {
      return res.status(503).json({ ok: false, error: 'WhatsApp belum tersambung. Scan QR dulu.' });
    }

    const jid = normalizeNumber(number) + '@s.whatsapp.net';
    await sock.sendMessage(jid, { text: String(message) });

    return res.json({ ok: true, to: jid });
  } catch (e) {
    console.error('[HTTP] /send error:', e.message);
    return res.status(500).json({ ok: false, error: e.message });
  }
});

app.listen(PORT, HOST, () => {
  console.log(`[HTTP] Gateway berjalan di http://${HOST}:${PORT}`);
});

connectWA().catch((e) => {
  console.error('[WA] gagal connect:', e.message);
  process.exit(1);
});

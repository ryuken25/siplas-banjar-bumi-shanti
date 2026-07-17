import { SessionOptions } from 'iron-session';
import { cookies } from 'next/headers';
import { sealData, unsealData } from 'iron-session';
import { sql } from './db';
import bcrypt from 'bcryptjs';

export interface SessionData {
  userId: string;
  role: 'admin' | 'petugas' | 'warga';
  name: string;
  email: string;
  status_akun: string;
}

const sessionOptions: SessionOptions = {
  password: process.env.SESSION_SECRET || 'complex-password-at-least-32-characters-long',
  cookieName: 'siplas-session',
  cookieOptions: {
    secure: process.env.NODE_ENV === 'production',
    httpOnly: true,
    sameSite: 'lax',
    maxAge: 60 * 60 * 24 * 7, // 7 days
    path: '/',
  },
};

export async function createSession(user: { id: string; role: string; name: string; email: string; status_akun: string }) {
  const session: SessionData = {
    userId: user.id,
    role: user.role as SessionData['role'],
    name: user.name,
    email: user.email,
    status_akun: user.status_akun,
  };
  const sealed = await sealData(session, sessionOptions);
  const cookieStore = await cookies();
  cookieStore.set(sessionOptions.cookieName, sealed, sessionOptions.cookieOptions);
  return session;
}

export async function getSession(): Promise<SessionData | null> {
  try {
    const cookieStore = await cookies();
    const cookie = cookieStore.get(sessionOptions.cookieName);
    if (!cookie?.value) return null;
    const session = await unsealData<SessionData>(cookie.value, sessionOptions);
    if (!session?.userId) return null;
    return session;
  } catch {
    return null;
  }
}

export async function destroySession() {
  const cookieStore = await cookies();
  cookieStore.delete(sessionOptions.cookieName);
}

export async function authenticate(email: string, password: string): Promise<SessionData | null> {
  const users = await sql`
    SELECT id, name, email, password_hash, role, status_akun
    FROM users WHERE email = ${email} LIMIT 1
  `;
  const user = users[0];
  if (!user) return null;
  
  const valid = await bcrypt.compare(password, user.password_hash);
  if (!valid) return null;

  if (user.status_akun !== 'aktif' && user.role !== 'admin') {
    return null; // Only admin can login when not aktif
  }

  return {
    userId: user.id,
    role: user.role,
    name: user.name,
    email: user.email,
    status_akun: user.status_akun,
  };
}

export async function registerUser(data: {
  name: string;
  email: string;
  password: string;
  nik: string;
  no_kk: string;
  no_telp: string;
  alamat: string;
}): Promise<{ user?: any; error?: string }> {
  // Check if email exists
  const existing = await sql`SELECT id FROM users WHERE email = ${data.email} LIMIT 1`;
  if (existing.length > 0) return { error: 'Email sudah terdaftar' };

  // Check NIK
  const existingNik = await sql`SELECT id FROM users WHERE nik = ${data.nik} LIMIT 1`;
  if (existingNik.length > 0) return { error: 'NIK sudah terdaftar' };

  const passwordHash = await bcrypt.hash(data.password, 10);
  
  const users = await sql`
    INSERT INTO users (name, email, password_hash, nik, no_kk, no_telp, alamat, role, status_akun)
    VALUES (${data.name}, ${data.email}, ${passwordHash}, ${data.nik}, ${data.no_kk}, ${data.no_telp}, ${data.alamat}, 'warga', 'pending')
    RETURNING id, name, email, role, status_akun
  `;

  // Notify admins
  const admins = await sql`SELECT id FROM users WHERE role = 'admin' AND status_akun = 'aktif'`;
  for (const admin of admins) {
    await sql`
      INSERT INTO notifikasi (user_id, judul, pesan, tipe, url)
      VALUES (${admin.id}, 'Pendaftaran Baru', ${'Warga baru mendaftar: ' + data.name + ' (' + data.email + '). Segera review dan setujui.'}, 'pendaftaran_baru', '/admin/pengguna')
    `;
  }

  return { user: users[0] };
}

export { sessionOptions };

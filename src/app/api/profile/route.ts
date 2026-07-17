import { NextResponse } from 'next/server';
import { getSession } from '@/lib/auth';
import { sql } from '@/lib/db';
import bcrypt from 'bcryptjs';

export async function GET() {
  try {
    const session = await getSession();
    if (!session) {
      return NextResponse.json({ error: 'Unauthorized' }, { status: 401 });
    }

    const users = await sql`
      SELECT id, name, email, nik, no_kk, no_telp, alamat, foto_profil, role, status_akun, created_at, updated_at
      FROM users WHERE id = ${session.userId}
    `;

    if (users.length === 0) {
      return NextResponse.json({ error: 'User tidak ditemukan' }, { status: 404 });
    }

    return NextResponse.json(users[0]);
  } catch (error) {
    console.error('Profile GET error:', error);
    return NextResponse.json({ error: 'Internal server error' }, { status: 500 });
  }
}

export async function PATCH(request: Request) {
  try {
    const session = await getSession();
    if (!session) {
      return NextResponse.json({ error: 'Unauthorized' }, { status: 401 });
    }

    const body = await request.json();
    const { name, no_telp, alamat, oldPassword, newPassword } = body;

    // Password change
    if (oldPassword && newPassword) {
      const users = await sql`SELECT password_hash FROM users WHERE id = ${session.userId}`;
      if (users.length === 0) {
        return NextResponse.json({ error: 'User tidak ditemukan' }, { status: 404 });
      }

      const valid = await bcrypt.compare(oldPassword, users[0].password_hash);
      if (!valid) {
        return NextResponse.json({ error: 'Password lama tidak sesuai' }, { status: 400 });
      }

      const newHash = await bcrypt.hash(newPassword, 10);
      await sql`UPDATE users SET password_hash = ${newHash}, updated_at = NOW() WHERE id = ${session.userId}`;

      return NextResponse.json({ success: true, message: 'Password berhasil diubah' });
    }

    // Profile update
    if (!name) {
      return NextResponse.json({ error: 'Nama wajib diisi' }, { status: 400 });
    }

    await sql`
      UPDATE users SET name = ${name}, no_telp = ${no_telp || null}, alamat = ${alamat || null}, updated_at = NOW()
      WHERE id = ${session.userId}
    `;

    const updated = await sql`
      SELECT id, name, email, nik, no_kk, no_telp, alamat, foto_profil, role, status_akun, created_at, updated_at
      FROM users WHERE id = ${session.userId}
    `;

    return NextResponse.json({ data: updated[0], message: 'Profil berhasil diperbarui' });
  } catch (error) {
    console.error('Profile PATCH error:', error);
    return NextResponse.json({ error: 'Internal server error' }, { status: 500 });
  }
}

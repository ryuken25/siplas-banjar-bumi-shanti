import { NextResponse } from 'next/server';
import { getSession } from '@/lib/auth';
import { sql } from '@/lib/db';
import bcrypt from 'bcryptjs';

export async function PATCH(request: Request, { params }: { params: { id: string } }) {
  try {
    const session = await getSession();
    if (!session || session.role !== 'admin') {
      return NextResponse.json({ error: 'Unauthorized' }, { status: 401 });
    }

    const { id } = params;
    const body = await request.json();
    const { name, email, password } = body;

    const existing = await sql`SELECT * FROM users WHERE id = ${id} AND role = 'petugas'`;
    if (existing.length === 0) {
      return NextResponse.json({ error: 'Petugas tidak ditemukan' }, { status: 404 });
    }

    if (email) {
      const emailCheck = await sql`SELECT id FROM users WHERE email = ${email} AND id != ${id} LIMIT 1`;
      if (emailCheck.length > 0) {
        return NextResponse.json({ error: 'Email sudah digunakan' }, { status: 400 });
      }
    }

    const now = new Date().toISOString();
    const updates: string[] = ['updated_at = $1'];
    const values: any[] = [now];
    let idx = 2;

    if (name) { updates.push(`name = $${idx++}`); values.push(name); }
    if (email) { updates.push(`email = $${idx++}`); values.push(email); }
    if (password) {
      const hash = await bcrypt.hash(password, 10);
      updates.push(`password_hash = $${idx++}`);
      values.push(hash);
    }
    values.push(id);

    await sql(`UPDATE users SET ${updates.join(', ')} WHERE id = $${idx}`, values);

    const updated = await sql`SELECT id, name, email, role, status_akun, created_at FROM users WHERE id = ${id}`;
    return NextResponse.json({ data: updated[0], message: 'Petugas berhasil diperbarui' });
  } catch (error) {
    console.error('Admin petugas update error:', error);
    return NextResponse.json({ error: 'Internal server error' }, { status: 500 });
  }
}

export async function DELETE(request: Request, { params }: { params: { id: string } }) {
  try {
    const session = await getSession();
    if (!session || session.role !== 'admin') {
      return NextResponse.json({ error: 'Unauthorized' }, { status: 401 });
    }

    const { id } = params;
    const existing = await sql`SELECT * FROM users WHERE id = ${id} AND role = 'petugas'`;
    if (existing.length === 0) {
      return NextResponse.json({ error: 'Petugas tidak ditemukan' }, { status: 404 });
    }

    await sql`UPDATE users SET status_akun = 'nonaktif', updated_at = NOW() WHERE id = ${id}`;

    return NextResponse.json({ success: true, message: 'Petugas berhasil dinonaktifkan' });
  } catch (error) {
    console.error('Admin petugas delete error:', error);
    return NextResponse.json({ error: 'Internal server error' }, { status: 500 });
  }
}

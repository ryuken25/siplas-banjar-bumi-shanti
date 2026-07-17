import { NextResponse } from 'next/server';
import { getSession } from '@/lib/auth';
import { sql } from '@/lib/db';
import bcrypt from 'bcryptjs';

export async function GET(request: Request) {
  try {
    const session = await getSession();
    if (!session || session.role !== 'admin') {
      return NextResponse.json({ error: 'Unauthorized' }, { status: 401 });
    }

    const { searchParams } = new URL(request.url);
    const search = searchParams.get('search');

    let query = `SELECT id, name, email, no_telp, foto_profil, role, status_akun, created_at FROM users WHERE role = 'petugas'`;
    const params: any[] = [];
    let paramIdx = 1;

    if (search) {
      query += ` AND (name ILIKE $${paramIdx} OR email ILIKE $${paramIdx})`;
      params.push(`%${search}%`);
      paramIdx++;
    }
    query += ` ORDER BY created_at DESC`;

    const data = params.length > 0 ? await sql(query, params) : await sql`SELECT id, name, email, no_telp, foto_profil, role, status_akun, created_at FROM users WHERE role = 'petugas' ORDER BY created_at DESC`;

    return NextResponse.json({ data });
  } catch (error) {
    console.error('Admin petugas list error:', error);
    return NextResponse.json({ error: 'Internal server error' }, { status: 500 });
  }
}

export async function POST(request: Request) {
  try {
    const session = await getSession();
    if (!session || session.role !== 'admin') {
      return NextResponse.json({ error: 'Unauthorized' }, { status: 401 });
    }

    const body = await request.json();
    const { name, email, password } = body;

    if (!name || !email || !password) {
      return NextResponse.json({ error: 'Nama, email, dan password wajib diisi' }, { status: 400 });
    }

    // Check email exists
    const existing = await sql`SELECT id FROM users WHERE email = ${email} LIMIT 1`;
    if (existing.length > 0) {
      return NextResponse.json({ error: 'Email sudah terdaftar' }, { status: 400 });
    }

    const passwordHash = await bcrypt.hash(password, 10);

    const users = await sql`
      INSERT INTO users (name, email, password_hash, role, status_akun)
      VALUES (${name}, ${email}, ${passwordHash}, 'petugas', 'aktif')
      RETURNING id, name, email, role, status_akun, created_at
    `;

    return NextResponse.json({ data: users[0], message: 'Petugas berhasil ditambahkan' });
  } catch (error) {
    console.error('Admin petugas create error:', error);
    return NextResponse.json({ error: 'Internal server error' }, { status: 500 });
  }
}

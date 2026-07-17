import { NextResponse } from 'next/server';
import { getSession } from '@/lib/auth';
import { sql } from '@/lib/db';

export async function GET() {
  try {
    const session = await getSession();
    if (!session || session.role !== 'admin') {
      return NextResponse.json({ error: 'Unauthorized' }, { status: 401 });
    }

    const data = await sql`SELECT * FROM tarif_iuran ORDER BY periode_mulai DESC`;
    return NextResponse.json({ data });
  } catch (error) {
    console.error('Admin tarif list error:', error);
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
    const { nominal, periode_mulai, keterangan, aktif } = body;

    if (!nominal || !periode_mulai) {
      return NextResponse.json({ error: 'Nominal dan periode mulai wajib diisi' }, { status: 400 });
    }

    // If aktif, deactivate others
    if (aktif) {
      await sql`UPDATE tarif_iuran SET aktif = false, updated_at = NOW() WHERE aktif = true`;
    }

    const data = await sql`
      INSERT INTO tarif_iuran (nominal, periode_mulai, keterangan, aktif)
      VALUES (${nominal}, ${periode_mulai}, ${keterangan || null}, ${aktif ?? true})
      RETURNING *
    `;

    return NextResponse.json({ data: data[0], message: 'Tarif berhasil ditambahkan' });
  } catch (error) {
    console.error('Admin tarif create error:', error);
    return NextResponse.json({ error: 'Internal server error' }, { status: 500 });
  }
}

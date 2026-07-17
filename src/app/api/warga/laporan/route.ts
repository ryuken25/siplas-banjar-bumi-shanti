import { NextRequest, NextResponse } from 'next/server';
import { getSession } from '@/lib/auth';
import { sql } from '@/lib/db';
import { writeFile, mkdir } from 'fs/promises';
import path from 'path';
import crypto from 'crypto';

export async function GET(request: NextRequest) {
  const session = await getSession();
  if (!session || session.role !== 'warga') {
    return NextResponse.json({ error: 'Unauthorized' }, { status: 401 });
  }

  const { searchParams } = new URL(request.url);
  const status = searchParams.get('status');
  const limit = parseInt(searchParams.get('limit') || '12', 10);
  const offset = parseInt(searchParams.get('offset') || '0', 10);

  let laporan;
  if (status && status !== 'semua') {
    laporan = await sql`
      SELECT ls.*, u.name as petugas_name
      FROM laporan_sampah ls
      LEFT JOIN users u ON ls.petugas_id = u.id
      WHERE ls.user_id = ${session.userId} AND ls.status = ${status}
      ORDER BY ls.created_at DESC
      LIMIT ${limit} OFFSET ${offset}
    `;
  } else {
    laporan = await sql`
      SELECT ls.*, u.name as petugas_name
      FROM laporan_sampah ls
      LEFT JOIN users u ON ls.petugas_id = u.id
      WHERE ls.user_id = ${session.userId}
      ORDER BY ls.created_at DESC
      LIMIT ${limit} OFFSET ${offset}
    `;
  }

  return NextResponse.json({ laporan, hasMore: laporan.length === limit });
}

export async function POST(request: NextRequest) {
  const session = await getSession();
  if (!session || session.role !== 'warga') {
    return NextResponse.json({ error: 'Unauthorized' }, { status: 401 });
  }

  try {
    const formData = await request.formData();
    const foto = formData.get('foto') as File | null;
    const jenis_sampah = formData.get('jenis_sampah') as string;
    const lokasi_text = formData.get('lokasi_text') as string;
    const latitude = formData.get('latitude') as string | null;
    const longitude = formData.get('longitude') as string | null;
    const keterangan = formData.get('keterangan') as string || '';

    if (!jenis_sampah || !lokasi_text) {
      return NextResponse.json({ error: 'Jenis sampah dan lokasi wajib diisi' }, { status: 400 });
    }

    let fotoUrl = '';
    if (foto && foto.size > 0) {
      const ext = foto.name.split('.').pop() || 'jpg';
      const filename = `${crypto.randomUUID()}.${ext}`;
      const uploadDir = path.join(process.cwd(), 'public', 'uploads', 'laporan');
      await mkdir(uploadDir, { recursive: true });
      const bytes = await foto.arrayBuffer();
      await writeFile(path.join(uploadDir, filename), Buffer.from(bytes));
      fotoUrl = `/uploads/laporan/${filename}`;
    }

    const lat = latitude ? parseFloat(latitude) : null;
    const lng = longitude ? parseFloat(longitude) : null;

    const result = await sql`
      INSERT INTO laporan_sampah (user_id, kode_laporan, jenis_sampah, lokasi_text, latitude, longitude, keterangan, foto, status)
      VALUES (${session.userId}, generate_kode_laporan(), ${jenis_sampah}, ${lokasi_text}, ${lat}, ${lng}, ${keterangan}, ${fotoUrl}, 'dikirim')
      RETURNING *
    `;

    // Notify all petugas
    const petugas = await sql`SELECT id FROM users WHERE role = 'petugas' AND status_akun = 'aktif'`;
    for (const p of petugas) {
      await sql`
        INSERT INTO notifikasi (user_id, judul, pesan, tipe, url)
        VALUES (${p.id}, 'Laporan Sampah Baru', ${`Warga ${session.name} melaporkan sampah ${jenis_sampah} di ${lokasi_text}`}, 'laporan_baru', '/petugas/laporan/${result[0].id}')
      `;
    }

    return NextResponse.json(result[0], { status: 201 });
  } catch (error) {
    console.error('Create laporan error:', error);
    return NextResponse.json({ error: 'Gagal membuat laporan' }, { status: 500 });
  }
}

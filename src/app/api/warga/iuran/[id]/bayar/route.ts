import { NextRequest, NextResponse } from 'next/server';
import { getSession } from '@/lib/auth';
import { sql } from '@/lib/db';
import { writeFile, mkdir } from 'fs/promises';
import path from 'path';
import crypto from 'crypto';

export async function POST(
  request: NextRequest,
  { params }: { params: { id: string } }
) {
  const session = await getSession();
  if (!session || session.role !== 'warga') {
    return NextResponse.json({ error: 'Unauthorized' }, { status: 401 });
  }

  try {
    // Verify iuran belongs to user
    const existing = await sql`
      SELECT * FROM iuran_bulanan
      WHERE id = ${params.id} AND user_id = ${session.userId}
      LIMIT 1
    `;

    if (!existing[0]) {
      return NextResponse.json({ error: 'Tagihan tidak ditemukan' }, { status: 404 });
    }

    if (existing[0].status !== 'belum_bayar' && existing[0].status !== 'ditolak') {
      return NextResponse.json({ error: 'Tagihan sudah dibayar atau sedang diverifikasi' }, { status: 400 });
    }

    const formData = await request.formData();
    const metode_bayar = formData.get('metode_bayar') as 'transfer' | 'tunai';
    const bukti_bayar_file = formData.get('bukti_bayar') as File | null;

    if (!metode_bayar) {
      return NextResponse.json({ error: 'Metode bayar wajib diisi' }, { status: 400 });
    }

    let buktiUrl = '';
    if (metode_bayar === 'transfer' && bukti_bayar_file && bukti_bayar_file.size > 0) {
      const ext = bukti_bayar_file.name.split('.').pop() || 'jpg';
      const filename = `${crypto.randomUUID()}.${ext}`;
      const uploadDir = path.join(process.cwd(), 'public', 'uploads', 'bukti-bayar');
      await mkdir(uploadDir, { recursive: true });
      const bytes = await bukti_bayar_file.arrayBuffer();
      await writeFile(path.join(uploadDir, filename), Buffer.from(bytes));
      buktiUrl = `/uploads/bukti-bayar/${filename}`;
    }

    const updated = await sql`
      UPDATE iuran_bulanan
      SET status = 'menunggu_verifikasi',
          metode_bayar = ${metode_bayar},
          bukti_bayar = ${buktiUrl || null},
          tanggal_bayar = NOW()
      WHERE id = ${params.id}
      RETURNING *
    `;

    // Notify petugas
    const petugas = await sql`SELECT id FROM users WHERE role = 'petugas' AND status_akun = 'aktif'`;
    for (const p of petugas) {
      await sql`
        INSERT INTO notifikasi (user_id, judul, pesan, tipe, url)
        VALUES (${p.id}, 'Pembayaran Iuran Baru', ${`Warga ${session.name} membayar iuran ${existing[0].kode_tagihan} via ${metode_bayar}`}, 'iuran_bayar', '/petugas/iuran/${updated[0].id}')
      `;
    }

    return NextResponse.json(updated[0]);
  } catch (error) {
    console.error('Bayar iuran error:', error);
    return NextResponse.json({ error: 'Gagal memproses pembayaran' }, { status: 500 });
  }
}

import { NextResponse } from 'next/server';
import { getSession } from '@/lib/auth';
import { sql } from '@/lib/db';

export async function POST(request: Request) {
  try {
    const session = await getSession();
    if (!session || session.role !== 'admin') {
      return NextResponse.json({ error: 'Unauthorized' }, { status: 401 });
    }

    const body = await request.json();
    const { bulan, tahun } = body;

    if (!bulan || !tahun) {
      return NextResponse.json({ error: 'Bulan dan tahun wajib diisi' }, { status: 400 });
    }

    // Get active tarif
    const tarifResult = await sql`SELECT nominal FROM tarif_iuran WHERE aktif = true ORDER BY periode_mulai DESC LIMIT 1`;
    if (tarifResult.length === 0) {
      return NextResponse.json({ error: 'Belum ada tarif aktif. Silakan tambahkan tarif terlebih dahulu.' }, { status: 400 });
    }
    const nominal = tarifResult[0].nominal;

    // Get active warga who don't have iuran for this period
    const warga = await sql`
      SELECT u.id FROM users u
      WHERE u.role = 'warga' AND u.status_akun = 'aktif'
      AND NOT EXISTS (
        SELECT 1 FROM iuran_bulanan ib WHERE ib.user_id = u.id AND ib.bulan = ${bulan} AND ib.tahun = ${tahun}
      )
    `;

    if (warga.length === 0) {
      return NextResponse.json({ message: 'Semua warga aktif sudah memiliki tagihan untuk periode ini', generated: 0 });
    }

    let generated = 0;
    for (const w of warga) {
      const kodeResult = await sql`SELECT generate_kode_tagihan(${bulan}, ${tahun}) as kode`;
      const kodeTagihan = kodeResult[0]?.kode || `IUR-${tahun}${String(bulan).padStart(2, '0')}-${generated + 1}`;

      await sql`
        INSERT INTO iuran_bulanan (kode_tagihan, user_id, bulan, tahun, nominal, status)
        VALUES (${kodeTagihan}, ${w.id}, ${bulan}, ${tahun}, ${nominal}, 'belum_bayar')
      `;
      generated++;
    }

    return NextResponse.json({ success: true, message: `Berhasil generate ${generated} tagihan`, generated });
  } catch (error) {
    console.error('Admin iuran generate error:', error);
    return NextResponse.json({ error: 'Internal server error' }, { status: 500 });
  }
}

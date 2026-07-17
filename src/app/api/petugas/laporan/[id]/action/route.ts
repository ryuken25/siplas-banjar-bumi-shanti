import { NextResponse } from 'next/server';
import { getSession } from '@/lib/auth';
import { sql } from '@/lib/db';

export async function POST(request: Request, { params }: { params: { id: string } }) {
  try {
    const session = await getSession();
    if (!session || session.role !== 'petugas') {
      return NextResponse.json({ error: 'Unauthorized' }, { status: 401 });
    }

    const { id } = params;
    const body = await request.json();
    const { action, alasan } = body;

    if (!['terima', 'proses', 'selesai', 'tolak'].includes(action)) {
      return NextResponse.json({ error: 'Aksi tidak valid' }, { status: 400 });
    }

    // Get current laporan
    const laporan = await sql`SELECT * FROM laporan_sampah WHERE id = ${id}`;
    if (laporan.length === 0) {
      return NextResponse.json({ error: 'Laporan tidak ditemukan' }, { status: 404 });
    }

    const current = laporan[0];
    let updateFields: any = { updated_at: new Date().toISOString() };
    let notifJudul = '';
    let notifPesan = '';

    switch (action) {
      case 'terima':
        if (current.status !== 'dikirim') {
          return NextResponse.json({ error: 'Laporan harus dalam status dikirim' }, { status: 400 });
        }
        updateFields.status = 'diterima';
        updateFields.petugas_id = session.userId;
        updateFields.tanggal_diterima = new Date().toISOString();
        notifJudul = 'Laporan Diterima';
        notifPesan = `Laporan ${current.kode_laporan} telah diterima oleh petugas dan akan segera diproses.`;
        break;

      case 'proses':
        if (current.status !== 'diterima') {
          return NextResponse.json({ error: 'Laporan harus dalam status diterima' }, { status: 400 });
        }
        updateFields.status = 'diproses';
        updateFields.tanggal_diproses = new Date().toISOString();
        notifJudul = 'Laporan Diproses';
        notifPesan = `Laporan ${current.kode_laporan} sedang dalam proses penanganan.`;
        break;

      case 'selesai':
        if (current.status !== 'diproses') {
          return NextResponse.json({ error: 'Laporan harus dalam status diproses' }, { status: 400 });
        }
        updateFields.status = 'selesai';
        updateFields.tanggal_selesai = new Date().toISOString();
        notifJudul = 'Laporan Selesai';
        notifPesan = `Laporan ${current.kode_laporan} telah selesai ditangani. Terima kasih atas laporannya!`;
        break;

      case 'tolak':
        if (current.status === 'selesai' || current.status === 'ditolak') {
          return NextResponse.json({ error: 'Laporan tidak dapat ditolak' }, { status: 400 });
        }
        if (!alasan) {
          return NextResponse.json({ error: 'Alasan penolakan wajib diisi' }, { status: 400 });
        }
        updateFields.status = 'ditolak';
        updateFields.alasan_tolak = alasan;
        notifJudul = 'Laporan Ditolak';
        notifPesan = `Laporan ${current.kode_laporan} ditolak. Alasan: ${alasan}`;
        break;
    }

    // Build dynamic UPDATE
    const setClauses: string[] = [];
    const values: any[] = [];
    let idx = 1;
    for (const [key, value] of Object.entries(updateFields)) {
      setClauses.push(`${key} = $${idx++}`);
      values.push(value);
    }
    values.push(id);

    await sql.query(
      `UPDATE laporan_sampah SET ${setClauses.join(', ')} WHERE id = $${idx}`,
      values
    );

    // Create notifikasi for warga
    await sql`
      INSERT INTO notifikasi (user_id, judul, pesan, tipe, url)
      VALUES (${current.user_id}, ${notifJudul}, ${notifPesan}, 'laporan', ${'/warga/laporan/' + id})
    `;

    return NextResponse.json({ success: true, message: `Laporan berhasil di${action === 'terima' ? 'terima' : action === 'proses' ? 'proses' : action === 'selesai' ? 'selesai' : 'tolak'}` });
  } catch (error) {
    console.error('Petugas laporan action error:', error);
    return NextResponse.json({ error: 'Internal server error' }, { status: 500 });
  }
}

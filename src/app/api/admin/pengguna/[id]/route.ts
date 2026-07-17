import { NextResponse } from 'next/server';
import { getSession } from '@/lib/auth';
import { sql } from '@/lib/db';

export async function POST(request: Request, { params }: { params: { id: string } }) {
  try {
    const session = await getSession();
    if (!session || session.role !== 'admin') {
      return NextResponse.json({ error: 'Unauthorized' }, { status: 401 });
    }

    const { id } = params;
    const body = await request.json();
    const { action, alasan } = body;

    if (!['approve', 'reject', 'nonaktifkan', 'aktifkan'].includes(action)) {
      return NextResponse.json({ error: 'Aksi tidak valid' }, { status: 400 });
    }

    const user = await sql`SELECT * FROM users WHERE id = ${id} AND role = 'warga'`;
    if (user.length === 0) {
      return NextResponse.json({ error: 'Pengguna tidak ditemukan' }, { status: 404 });
    }

    const currentUser = user[0];
    const now = new Date().toISOString();
    let notifJudul = '';
    let notifPesan = '';

    switch (action) {
      case 'approve':
        await sql`UPDATE users SET status_akun = 'aktif', alasan_tolak_akun = NULL, updated_at = ${now} WHERE id = ${id}`;
        notifJudul = 'Akun Disetujui';
        notifPesan = 'Selamat! Akun Anda telah disetujui. Anda sekarang dapat menggunakan semua fitur SIPLAS.';
        break;

      case 'reject':
        if (!alasan) return NextResponse.json({ error: 'Alasan penolakan wajib diisi' }, { status: 400 });
        await sql`UPDATE users SET status_akun = 'nonaktif', alasan_tolak_akun = ${alasan}, updated_at = ${now} WHERE id = ${id}`;
        notifJudul = 'Akun Ditolak';
        notifPesan = `Maaf, pendaftaran akun Anda ditolak. Alasan: ${alasan}. Silakan hubungi admin untuk informasi lebih lanjut.`;
        break;

      case 'nonaktifkan':
        await sql`UPDATE users SET status_akun = 'nonaktif', updated_at = ${now} WHERE id = ${id}`;
        notifJudul = 'Akun Dinonaktifkan';
        notifPesan = 'Akun Anda telah dinonaktifkan oleh admin. Silakan hubungi admin untuk informasi lebih lanjut.';
        break;

      case 'aktifkan':
        await sql`UPDATE users SET status_akun = 'aktif', alasan_tolak_akun = NULL, updated_at = ${now} WHERE id = ${id}`;
        notifJudul = 'Akun Diaktifkan Kembali';
        notifPesan = 'Akun Anda telah diaktifkan kembali. Anda dapat menggunakan SIPLAS seperti biasa.';
        break;
    }

    // Create notifikasi
    await sql`
      INSERT INTO notifikasi (user_id, judul, pesan, tipe, url)
      VALUES (${id}, ${notifJudul}, ${notifPesan}, 'akun', '/profile')
    `;

    return NextResponse.json({ success: true, message: `Pengguna berhasil di${action === 'approve' ? 'setujui' : action === 'reject' ? 'tolak' : action === 'nonaktifkan' ? 'nonaktifkan' : 'aktifkan'}` });
  } catch (error) {
    console.error('Admin pengguna action error:', error);
    return NextResponse.json({ error: 'Internal server error' }, { status: 500 });
  }
}

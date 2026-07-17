import { NextRequest, NextResponse } from 'next/server';
import { registerUser } from '@/lib/auth';

export async function POST(request: NextRequest) {
  try {
    const body = await request.json();
    const { name, email, password, nik, no_kk, no_telp, alamat } = body;

    // Validate required fields
    if (!name || !email || !password || !nik || !no_kk || !no_telp || !alamat) {
      return NextResponse.json(
        { error: 'Semua field wajib diisi' },
        { status: 400 }
      );
    }

    // Validate email format
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(email)) {
      return NextResponse.json(
        { error: 'Format email tidak valid' },
        { status: 400 }
      );
    }

    // Validate password length
    if (password.length < 8) {
      return NextResponse.json(
        { error: 'Password minimal 8 karakter' },
        { status: 400 }
      );
    }

    // Validate NIK (16 digits)
    if (!/^\d{16}$/.test(nik)) {
      return NextResponse.json(
        { error: 'NIK harus 16 digit angka' },
        { status: 400 }
      );
    }

    // Validate No KK (16 digits)
    if (!/^\d{16}$/.test(no_kk)) {
      return NextResponse.json(
        { error: 'Nomor KK harus 16 digit angka' },
        { status: 400 }
      );
    }

    // Validate phone number
    if (!/^[0-9+\-\s]{10,15}$/.test(no_telp)) {
      return NextResponse.json(
        { error: 'Nomor telepon tidak valid' },
        { status: 400 }
      );
    }

    const result = await registerUser({
      name: name.trim(),
      email: email.trim().toLowerCase(),
      password,
      nik,
      no_kk,
      no_telp: no_telp.trim(),
      alamat: alamat.trim(),
    });

    if (result.error) {
      return NextResponse.json(
        { error: result.error },
        { status: 409 }
      );
    }

    return NextResponse.json({ success: true });
  } catch (error) {
    console.error('Register error:', error);
    return NextResponse.json(
      { error: 'Terjadi kesalahan server' },
      { status: 500 }
    );
  }
}

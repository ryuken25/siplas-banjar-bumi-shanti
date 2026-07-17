'use client';

import { useState, FormEvent } from 'react';
import { useRouter } from 'next/navigation';
import Link from 'next/link';
import { toast } from 'sonner';
import {
  Leaf,
  User,
  Mail,
  Lock,
  CreditCard,
  Phone,
  MapPin,
  Loader2,
  Eye,
  EyeOff,
} from 'lucide-react';

interface FormData {
  name: string;
  email: string;
  password: string;
  confirmPassword: string;
  nik: string;
  no_kk: string;
  no_telp: string;
  alamat: string;
}

interface FormErrors {
  [key: string]: string;
}

export default function RegisterPage() {
  const router = useRouter();
  const [form, setForm] = useState<FormData>({
    name: '',
    email: '',
    password: '',
    confirmPassword: '',
    nik: '',
    no_kk: '',
    no_telp: '',
    alamat: '',
  });
  const [errors, setErrors] = useState<FormErrors>({});
  const [showPassword, setShowPassword] = useState(false);
  const [showConfirmPassword, setShowConfirmPassword] = useState(false);
  const [loading, setLoading] = useState(false);

  function updateField(field: keyof FormData, value: string) {
    setForm((prev) => ({ ...prev, [field]: value }));
    if (errors[field]) {
      setErrors((prev) => {
        const next = { ...prev };
        delete next[field];
        return next;
      });
    }
  }

  function validate(): FormErrors {
    const newErrors: FormErrors = {};

    if (!form.name.trim()) newErrors.name = 'Nama wajib diisi';

    if (!form.email.trim()) {
      newErrors.email = 'Email wajib diisi';
    } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(form.email)) {
      newErrors.email = 'Format email tidak valid';
    }

    if (!form.password) {
      newErrors.password = 'Password wajib diisi';
    } else if (form.password.length < 8) {
      newErrors.password = 'Password minimal 8 karakter';
    }

    if (!form.confirmPassword) {
      newErrors.confirmPassword = 'Konfirmasi password wajib diisi';
    } else if (form.password !== form.confirmPassword) {
      newErrors.confirmPassword = 'Password tidak cocok';
    }

    if (!form.nik) {
      newErrors.nik = 'NIK wajib diisi';
    } else if (!/^\d{16}$/.test(form.nik)) {
      newErrors.nik = 'NIK harus 16 digit angka';
    }

    if (!form.no_kk) {
      newErrors.no_kk = 'Nomor KK wajib diisi';
    } else if (!/^\d{16}$/.test(form.no_kk)) {
      newErrors.no_kk = 'Nomor KK harus 16 digit angka';
    }

    if (!form.no_telp) {
      newErrors.no_telp = 'Nomor telepon wajib diisi';
    } else if (!/^[0-9+\-\s]{10,15}$/.test(form.no_telp)) {
      newErrors.no_telp = 'Nomor telepon tidak valid';
    }

    if (!form.alamat.trim()) newErrors.alamat = 'Alamat wajib diisi';

    return newErrors;
  }

  async function handleSubmit(e: FormEvent) {
    e.preventDefault();
    const validationErrors = validate();

    if (Object.keys(validationErrors).length > 0) {
      setErrors(validationErrors);
      toast.error('Silakan perbaiki kesalahan pada form');
      return;
    }

    setLoading(true);
    try {
      const res = await fetch('/api/auth/register', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
          name: form.name.trim(),
          email: form.email.trim().toLowerCase(),
          password: form.password,
          nik: form.nik,
          no_kk: form.no_kk,
          no_telp: form.no_telp.trim(),
          alamat: form.alamat.trim(),
        }),
      });

      const data = await res.json();

      if (!res.ok) {
        toast.error(data.error || 'Registrasi gagal');
        return;
      }

      toast.success('Registrasi berhasil! Menunggu persetujuan admin.');
      router.push('/auth/pending');
    } catch {
      toast.error('Terjadi kesalahan jaringan');
    } finally {
      setLoading(false);
    }
  }

  function inputClass(field: string) {
    return `w-full pl-10 pr-4 py-2.5 border rounded-xl text-sm focus:outline-none transition-colors ${
      errors[field]
        ? 'border-red-300 focus:ring-2 focus:ring-red-500 focus:border-red-500'
        : 'border-gray-300 focus:ring-2 focus:ring-green-500 focus:border-green-500'
    }`;
  }

  return (
    <div className="min-h-screen bg-gradient-to-br from-green-50 via-emerald-50 to-teal-50 flex items-center justify-center p-4 py-10">
      <div className="w-full max-w-lg">
        {/* Logo & Header */}
        <div className="text-center mb-8">
          <div className="inline-flex items-center justify-center w-16 h-16 bg-green-600 rounded-2xl shadow-lg shadow-green-200 mb-4">
            <Leaf className="w-8 h-8 text-white" />
          </div>
          <h1 className="text-2xl font-bold text-gray-900">SIPLAS BBS</h1>
          <p className="text-gray-500 mt-1">Daftar Akun Warga Baru</p>
        </div>

        {/* Register Card */}
        <div className="bg-white rounded-2xl shadow-xl shadow-gray-200/50 border border-gray-100 p-8">
          <h2 className="text-xl font-semibold text-gray-900 mb-1">Buat Akun</h2>
          <p className="text-sm text-gray-500 mb-6">
            Lengkapi data diri Anda untuk mendaftar
          </p>

          <form onSubmit={handleSubmit} className="space-y-4">
            {/* Name */}
            <div>
              <label htmlFor="name" className="block text-sm font-medium text-gray-700 mb-1.5">
                Nama Lengkap
              </label>
              <div className="relative">
                <User className="absolute left-3 top-1/2 -translate-y-1/2 w-4.5 h-4.5 text-gray-400" />
                <input
                  id="name"
                  type="text"
                  value={form.name}
                  onChange={(e) => updateField('name', e.target.value)}
                  placeholder="Masukkan nama lengkap"
                  className={inputClass('name')}
                  disabled={loading}
                />
              </div>
              {errors.name && <p className="text-xs text-red-500 mt-1">{errors.name}</p>}
            </div>

            {/* Email */}
            <div>
              <label htmlFor="email" className="block text-sm font-medium text-gray-700 mb-1.5">
                Email
              </label>
              <div className="relative">
                <Mail className="absolute left-3 top-1/2 -translate-y-1/2 w-4.5 h-4.5 text-gray-400" />
                <input
                  id="email"
                  type="email"
                  value={form.email}
                  onChange={(e) => updateField('email', e.target.value)}
                  placeholder="nama@email.com"
                  className={inputClass('email')}
                  disabled={loading}
                />
              </div>
              {errors.email && <p className="text-xs text-red-500 mt-1">{errors.email}</p>}
            </div>

            {/* Password */}
            <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
              <div>
                <label htmlFor="password" className="block text-sm font-medium text-gray-700 mb-1.5">
                  Password
                </label>
                <div className="relative">
                  <Lock className="absolute left-3 top-1/2 -translate-y-1/2 w-4.5 h-4.5 text-gray-400" />
                  <input
                    id="password"
                    type={showPassword ? 'text' : 'password'}
                    value={form.password}
                    onChange={(e) => updateField('password', e.target.value)}
                    placeholder="Min. 8 karakter"
                    className={`${inputClass('password')} pr-10`}
                    disabled={loading}
                  />
                  <button
                    type="button"
                    onClick={() => setShowPassword(!showPassword)}
                    className="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600"
                  >
                    {showPassword ? <EyeOff className="w-4 h-4" /> : <Eye className="w-4 h-4" />}
                  </button>
                </div>
                {errors.password && <p className="text-xs text-red-500 mt-1">{errors.password}</p>}
              </div>

              <div>
                <label htmlFor="confirmPassword" className="block text-sm font-medium text-gray-700 mb-1.5">
                  Konfirmasi
                </label>
                <div className="relative">
                  <Lock className="absolute left-3 top-1/2 -translate-y-1/2 w-4.5 h-4.5 text-gray-400" />
                  <input
                    id="confirmPassword"
                    type={showConfirmPassword ? 'text' : 'password'}
                    value={form.confirmPassword}
                    onChange={(e) => updateField('confirmPassword', e.target.value)}
                    placeholder="Ulangi password"
                    className={`${inputClass('confirmPassword')} pr-10`}
                    disabled={loading}
                  />
                  <button
                    type="button"
                    onClick={() => setShowConfirmPassword(!showConfirmPassword)}
                    className="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600"
                  >
                    {showConfirmPassword ? <EyeOff className="w-4 h-4" /> : <Eye className="w-4 h-4" />}
                  </button>
                </div>
                {errors.confirmPassword && <p className="text-xs text-red-500 mt-1">{errors.confirmPassword}</p>}
              </div>
            </div>

            {/* NIK */}
            <div>
              <label htmlFor="nik" className="block text-sm font-medium text-gray-700 mb-1.5">
                NIK
              </label>
              <div className="relative">
                <CreditCard className="absolute left-3 top-1/2 -translate-y-1/2 w-4.5 h-4.5 text-gray-400" />
                <input
                  id="nik"
                  type="text"
                  value={form.nik}
                  onChange={(e) => updateField('nik', e.target.value.replace(/\D/g, '').slice(0, 16))}
                  placeholder="16 digit NIK"
                  maxLength={16}
                  className={inputClass('nik')}
                  disabled={loading}
                />
              </div>
              {errors.nik && <p className="text-xs text-red-500 mt-1">{errors.nik}</p>}
            </div>

            {/* No KK */}
            <div>
              <label htmlFor="no_kk" className="block text-sm font-medium text-gray-700 mb-1.5">
                Nomor Kartu Keluarga
              </label>
              <div className="relative">
                <CreditCard className="absolute left-3 top-1/2 -translate-y-1/2 w-4.5 h-4.5 text-gray-400" />
                <input
                  id="no_kk"
                  type="text"
                  value={form.no_kk}
                  onChange={(e) => updateField('no_kk', e.target.value.replace(/\D/g, '').slice(0, 16))}
                  placeholder="16 digit Nomor KK"
                  maxLength={16}
                  className={inputClass('no_kk')}
                  disabled={loading}
                />
              </div>
              {errors.no_kk && <p className="text-xs text-red-500 mt-1">{errors.no_kk}</p>}
            </div>

            {/* No Telp */}
            <div>
              <label htmlFor="no_telp" className="block text-sm font-medium text-gray-700 mb-1.5">
                Nomor Telepon
              </label>
              <div className="relative">
                <Phone className="absolute left-3 top-1/2 -translate-y-1/2 w-4.5 h-4.5 text-gray-400" />
                <input
                  id="no_telp"
                  type="tel"
                  value={form.no_telp}
                  onChange={(e) => updateField('no_telp', e.target.value)}
                  placeholder="08xxxxxxxxxx"
                  className={inputClass('no_telp')}
                  disabled={loading}
                />
              </div>
              {errors.no_telp && <p className="text-xs text-red-500 mt-1">{errors.no_telp}</p>}
            </div>

            {/* Alamat */}
            <div>
              <label htmlFor="alamat" className="block text-sm font-medium text-gray-700 mb-1.5">
                Alamat
              </label>
              <div className="relative">
                <MapPin className="absolute left-3 top-3 w-4.5 h-4.5 text-gray-400" />
                <textarea
                  id="alamat"
                  value={form.alamat}
                  onChange={(e) => updateField('alamat', e.target.value)}
                  placeholder="Alamat lengkap"
                  rows={3}
                  className={`w-full pl-10 pr-4 py-2.5 border rounded-xl text-sm focus:outline-none transition-colors resize-none ${
                    errors.alamat
                      ? 'border-red-300 focus:ring-2 focus:ring-red-500 focus:border-red-500'
                      : 'border-gray-300 focus:ring-2 focus:ring-green-500 focus:border-green-500'
                  }`}
                  disabled={loading}
                />
              </div>
              {errors.alamat && <p className="text-xs text-red-500 mt-1">{errors.alamat}</p>}
            </div>

            {/* Submit Button */}
            <button
              type="submit"
              disabled={loading}
              className="w-full py-2.5 px-4 bg-green-600 hover:bg-green-700 disabled:bg-green-400 text-white font-medium rounded-xl text-sm transition-colors focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 flex items-center justify-center gap-2"
            >
              {loading ? (
                <>
                  <Loader2 className="w-4 h-4 animate-spin" />
                  Mendaftarkan...
                </>
              ) : (
                'Daftar'
              )}
            </button>
          </form>
        </div>

        {/* Login Link */}
        <p className="text-center text-sm text-gray-500 mt-6">
          Sudah punya akun?{' '}
          <Link
            href="/auth/login"
            className="font-medium text-green-600 hover:text-green-700 transition-colors"
          >
            Masuk di sini
          </Link>
        </p>
      </div>
    </div>
  );
}

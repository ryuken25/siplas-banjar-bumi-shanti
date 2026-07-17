'use client';

import Link from 'next/link';
import { Leaf, Clock, ArrowLeft } from 'lucide-react';

export default function PendingPage() {
  return (
    <div className="min-h-screen bg-gradient-to-br from-green-50 via-emerald-50 to-teal-50 flex items-center justify-center p-4">
      <div className="w-full max-w-md text-center">
        {/* Icon */}
        <div className="inline-flex items-center justify-center w-20 h-20 bg-amber-100 rounded-full mb-6">
          <div className="relative">
            <Leaf className="w-10 h-10 text-green-600" />
            <Clock className="absolute -bottom-1 -right-1 w-5 h-5 text-amber-600 bg-amber-100 rounded-full" />
          </div>
        </div>

        {/* Content */}
        <div className="bg-white rounded-2xl shadow-xl shadow-gray-200/50 border border-gray-100 p-8">
          <h1 className="text-xl font-semibold text-gray-900 mb-3">
            Menunggu Persetujuan
          </h1>
          <p className="text-gray-500 text-sm leading-relaxed mb-2">
            Akun Anda sedang menunggu persetujuan admin. Silakan tunggu hingga
            akun Anda diverifikasi.
          </p>
          <p className="text-gray-400 text-xs mb-6">
            Proses ini biasanya memakan waktu 1×24 jam. Anda akan menerima
            notifikasi setelah akun disetujui.
          </p>

          <div className="inline-flex items-center gap-2 text-sm text-amber-700 bg-amber-50 border border-amber-200 rounded-xl px-4 py-2.5">
            <Clock className="w-4 h-4" />
            Status: Menunggu Verifikasi
          </div>
        </div>

        {/* Back to Login */}
        <Link
          href="/auth/login"
          className="inline-flex items-center gap-2 text-sm text-gray-500 hover:text-green-600 mt-6 transition-colors"
        >
          <ArrowLeft className="w-4 h-4" />
          Kembali ke halaman login
        </Link>
      </div>
    </div>
  );
}

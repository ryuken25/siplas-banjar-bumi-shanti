import Link from 'next/link';
import { Leaf, Shield, FileText, CreditCard, Users, BarChart3 } from 'lucide-react';

export default function HomePage() {
  return (
    <div className="min-h-screen bg-gradient-to-br from-brand-50 via-white to-emerald-50">
      {/* Header */}
      <header className="fixed top-0 w-full bg-white/80 backdrop-blur-md border-b border-brand-100 z-50">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
          <div className="flex items-center gap-2">
            <div className="w-8 h-8 bg-brand-500 rounded-lg flex items-center justify-center">
              <Leaf className="w-5 h-5 text-white" />
            </div>
            <span className="text-xl font-bold text-gray-900">SIPLAS</span>
          </div>
          <div className="flex items-center gap-3">
            <Link
              href="/auth/login"
              className="px-4 py-2 text-sm font-medium text-gray-700 hover:text-brand-600 transition-colors"
            >
              Masuk
            </Link>
            <Link
              href="/auth/register"
              className="px-4 py-2 text-sm font-medium text-white bg-brand-500 hover:bg-brand-600 rounded-lg transition-colors shadow-sm"
            >
              Daftar
            </Link>
          </div>
        </div>
      </header>

      {/* Hero */}
      <section className="pt-32 pb-20 px-4">
        <div className="max-w-4xl mx-auto text-center">
          <div className="inline-flex items-center gap-2 px-4 py-1.5 bg-brand-100 text-brand-700 rounded-full text-sm font-medium mb-6">
            <Leaf className="w-4 h-4" />
            Banjar Bumi Shanti
          </div>
          <h1 className="text-4xl sm:text-5xl lg:text-6xl font-bold text-gray-900 leading-tight mb-6">
            Sistem Pengelolaan
            <br />
            <span className="text-brand-600">Sampah Digital</span>
          </h1>
          <p className="text-lg text-gray-600 max-w-2xl mx-auto mb-10">
            Laporkan sampah, kelola iuran, dan pantau penyelesaian — semua dalam satu platform terintegrasi untuk warga Banjar Bumi Shanti.
          </p>
          <div className="flex flex-col sm:flex-row gap-4 justify-center">
            <Link
              href="/auth/register"
              className="px-8 py-3.5 text-base font-semibold text-white bg-brand-500 hover:bg-brand-600 rounded-xl transition-all shadow-lg shadow-brand-500/25 hover:shadow-xl hover:shadow-brand-500/30"
            >
              Daftar Sekarang
            </Link>
            <Link
              href="/auth/login"
              className="px-8 py-3.5 text-base font-semibold text-gray-700 bg-white hover:bg-gray-50 rounded-xl transition-all border border-gray-200 shadow-sm"
            >
              Masuk
            </Link>
          </div>
        </div>
      </section>

      {/* Features */}
      <section className="py-20 px-4 bg-white">
        <div className="max-w-6xl mx-auto">
          <h2 className="text-3xl font-bold text-center text-gray-900 mb-4">Fitur Utama</h2>
          <p className="text-gray-600 text-center mb-12 max-w-xl mx-auto">
            Platform lengkap untuk pengelolaan sampah dan iuran warga
          </p>
          <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            {[
              {
                icon: FileText,
                title: 'Lapor Sampah',
                desc: 'Laporkan sampah dengan foto dan lokasi. Lacak status penyelesaian secara real-time.',
                color: 'bg-blue-50 text-blue-600',
              },
              {
                icon: CreditCard,
                title: 'Iuran Bulanan',
                desc: 'Bayar iuran dan upload bukti transfer. Riwayat pembayaran tersimpan rapi.',
                color: 'bg-purple-50 text-purple-600',
              },
              {
                icon: Shield,
                title: 'Verifikasi Petugas',
                desc: 'Petugas dapat memverifikasi laporan dan pembayaran dengan mudah.',
                color: 'bg-amber-50 text-amber-600',
              },
              {
                icon: Users,
                title: 'Manajemen Warga',
                desc: 'Admin dapat mengelola akun warga, menyetujui pendaftaran, dan mengatur role.',
                color: 'bg-pink-50 text-pink-600',
              },
              {
                icon: BarChart3,
                title: 'Dashboard Statistik',
                desc: 'Pantau statistik laporan, iuran terkumpul, dan tingkat penyelesaian.',
                color: 'bg-emerald-50 text-emerald-600',
              },
              {
                icon: Leaf,
                title: 'Ramah Lingkungan',
                desc: 'Bersama kita wujudkan Banjar Bumi Shanti yang bersih dan lestari.',
                color: 'bg-green-50 text-green-600',
              },
            ].map((feature, i) => (
              <div key={i} className="p-6 rounded-2xl border border-gray-100 hover:border-brand-200 hover:shadow-lg transition-all group">
                <div className={`w-12 h-12 rounded-xl ${feature.color} flex items-center justify-center mb-4 group-hover:scale-110 transition-transform`}>
                  <feature.icon className="w-6 h-6" />
                </div>
                <h3 className="text-lg font-semibold text-gray-900 mb-2">{feature.title}</h3>
                <p className="text-gray-600 text-sm leading-relaxed">{feature.desc}</p>
              </div>
            ))}
          </div>
        </div>
      </section>

      {/* CTA */}
      <section className="py-20 px-4">
        <div className="max-w-3xl mx-auto text-center">
          <div className="bg-gradient-to-r from-brand-500 to-emerald-600 rounded-3xl p-12 text-white">
            <h2 className="text-3xl font-bold mb-4">Siap Bergabung?</h2>
            <p className="text-brand-100 mb-8 max-w-md mx-auto">
              Daftar sekarang dan mulai berkontribusi untuk lingkungan yang lebih bersih.
            </p>
            <Link
              href="/auth/register"
              className="inline-block px-8 py-3.5 text-base font-semibold text-brand-700 bg-white hover:bg-brand-50 rounded-xl transition-all shadow-lg"
            >
              Buat Akun
            </Link>
          </div>
        </div>
      </section>

      {/* Footer */}
      <footer className="py-8 px-4 bg-gray-900 text-gray-400">
        <div className="max-w-6xl mx-auto flex flex-col sm:flex-row items-center justify-between gap-4">
          <div className="flex items-center gap-2">
            <div className="w-6 h-6 bg-brand-500 rounded flex items-center justify-center">
              <Leaf className="w-4 h-4 text-white" />
            </div>
            <span className="font-semibold text-white">SIPLAS</span>
          </div>
          <p className="text-sm">© 2026 Banjar Bumi Shanti. Hak cipta dilindungi.</p>
        </div>
      </footer>
    </div>
  );
}

'use client';

import { useState, useRef, type DragEvent, type ChangeEvent } from 'react';
import { useRouter } from 'next/navigation';
import { toast } from 'sonner';
import { Upload, X, Loader2, MapPin, Send } from 'lucide-react';
import { JENIS_SAMPAH_LABEL, JENIS_SAMPAH_ICON } from '@/lib/utils';

const JENIS_OPTIONS = ['organik', 'anorganik', 'b3', 'campuran'] as const;

export default function LaporSampahPage() {
  const router = useRouter();
  const fileInputRef = useRef<HTMLInputElement>(null);
  const [foto, setFoto] = useState<File | null>(null);
  const [preview, setPreview] = useState<string | null>(null);
  const [jenisSampah, setJenisSampah] = useState('');
  const [lokasi, setLokasi] = useState('');
  const [keterangan, setKeterangan] = useState('');
  const [latitude, setLatitude] = useState('');
  const [longitude, setLongitude] = useState('');
  const [loading, setLoading] = useState(false);
  const [dragOver, setDragOver] = useState(false);

  function handleFile(file: File) {
    setFoto(file);
    const reader = new FileReader();
    reader.onloadend = () => setPreview(reader.result as string);
    reader.readAsDataURL(file);
  }

  function onDrop(e: DragEvent) {
    e.preventDefault();
    setDragOver(false);
    const file = e.dataTransfer.files?.[0];
    if (file && file.type.startsWith('image/')) handleFile(file);
  }

  function onChange(e: ChangeEvent<HTMLInputElement>) {
    const file = e.target.files?.[0];
    if (file) handleFile(file);
  }

  function removeFoto() {
    setFoto(null);
    setPreview(null);
    if (fileInputRef.current) fileInputRef.current.value = '';
  }

  async function handleSubmit(e: React.FormEvent) {
    e.preventDefault();
    if (!jenisSampah) { toast.error('Pilih jenis sampah'); return; }
    if (!lokasi.trim()) { toast.error('Isi lokasi sampah'); return; }

    setLoading(true);
    try {
      const fd = new FormData();
      if (foto) fd.append('foto', foto);
      fd.append('jenis_sampah', jenisSampah);
      fd.append('lokasi_text', lokasi);
      fd.append('keterangan', keterangan);
      if (latitude) fd.append('latitude', latitude);
      if (longitude) fd.append('longitude', longitude);

      const res = await fetch('/api/warga/laporan', { method: 'POST', body: fd });
      if (!res.ok) {
        const err = await res.json();
        throw new Error(err.error || 'Gagal mengirim laporan');
      }

      toast.success('Laporan berhasil dikirim!');
      router.push('/warga/laporan-saya');
    } catch (err: any) {
      toast.error(err.message);
    } finally {
      setLoading(false);
    }
  }

  return (
    <div className="min-h-screen bg-gray-50 p-4 md:p-8">
      <div className="max-w-2xl mx-auto">
        <h1 className="text-2xl font-bold text-gray-900 mb-6">Lapor Sampah</h1>

        <form onSubmit={handleSubmit} className="space-y-5 bg-white rounded-xl p-6 shadow-sm border border-gray-100">
          {/* Foto Upload */}
          <div>
            <label className="block text-sm font-medium text-gray-700 mb-2">Foto Sampah</label>
            {preview ? (
              <div className="relative rounded-lg overflow-hidden border border-gray-200">
                <img src={preview} alt="Preview" className="w-full max-h-64 object-cover" />
                <button
                  type="button"
                  onClick={removeFoto}
                  className="absolute top-2 right-2 p-1.5 bg-red-500 text-white rounded-full hover:bg-red-600 transition"
                >
                  <X className="w-4 h-4" />
                </button>
              </div>
            ) : (
              <div
                onDragOver={(e) => { e.preventDefault(); setDragOver(true); }}
                onDragLeave={() => setDragOver(false)}
                onDrop={onDrop}
                onClick={() => fileInputRef.current?.click()}
                className={`border-2 border-dashed rounded-lg p-8 text-center cursor-pointer transition ${
                  dragOver ? 'border-green-500 bg-green-50' : 'border-gray-300 hover:border-green-400'
                }`}
              >
                <Upload className="w-10 h-10 text-gray-400 mx-auto mb-2" />
                <p className="text-sm text-gray-600">
                  Klik atau drag & drop foto di sini
                </p>
                <p className="text-xs text-gray-400 mt-1">PNG, JPG, WEBP (maks 5MB)</p>
              </div>
            )}
            <input ref={fileInputRef} type="file" accept="image/*" onChange={onChange} className="hidden" />
          </div>

          {/* Jenis Sampah */}
          <div>
            <label className="block text-sm font-medium text-gray-700 mb-2">Jenis Sampah *</label>
            <div className="grid grid-cols-2 gap-3">
              {JENIS_OPTIONS.map((j) => (
                <button
                  key={j}
                  type="button"
                  onClick={() => setJenisSampah(j)}
                  className={`flex items-center gap-2 p-3 rounded-lg border-2 text-sm font-medium transition ${
                    jenisSampah === j
                      ? 'border-green-500 bg-green-50 text-green-700'
                      : 'border-gray-200 hover:border-gray-300 text-gray-700'
                  }`}
                >
                  <span className="text-lg">{JENIS_SAMPAH_ICON[j]}</span>
                  {JENIS_SAMPAH_LABEL[j]}
                </button>
              ))}
            </div>
          </div>

          {/* Lokasi */}
          <div>
            <label className="block text-sm font-medium text-gray-700 mb-2">Lokasi Sampah *</label>
            <input
              type="text"
              value={lokasi}
              onChange={(e) => setLokasi(e.target.value)}
              placeholder="Contoh: Depan rumah, Jl. Mawar No. 10"
              className="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 outline-none text-sm"
            />
          </div>

          {/* Koordinat (opsional) */}
          <div>
            <label className="flex items-center gap-1 text-sm font-medium text-gray-700 mb-2">
              <MapPin className="w-4 h-4" /> Koordinat (opsional)
            </label>
            <div className="grid grid-cols-2 gap-3">
              <input
                type="text"
                value={latitude}
                onChange={(e) => setLatitude(e.target.value)}
                placeholder="Latitude"
                className="px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 outline-none text-sm"
              />
              <input
                type="text"
                value={longitude}
                onChange={(e) => setLongitude(e.target.value)}
                placeholder="Longitude"
                className="px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 outline-none text-sm"
              />
            </div>
          </div>

          {/* Keterangan */}
          <div>
            <label className="block text-sm font-medium text-gray-700 mb-2">Keterangan</label>
            <textarea
              value={keterangan}
              onChange={(e) => setKeterangan(e.target.value)}
              rows={3}
              placeholder="Deskripsi singkat tentang kondisi sampah..."
              className="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 outline-none text-sm resize-none"
            />
          </div>

          {/* Submit */}
          <button
            type="submit"
            disabled={loading}
            className="w-full flex items-center justify-center gap-2 bg-green-600 text-white py-3 rounded-lg hover:bg-green-700 transition font-medium disabled:opacity-50 disabled:cursor-not-allowed"
          >
            {loading ? (
              <><Loader2 className="w-5 h-5 animate-spin" /> Mengirim...</>
            ) : (
              <><Send className="w-5 h-5" /> Kirim Laporan</>
            )}
          </button>
        </form>
      </div>
    </div>
  );
}

'use client';

import { useEffect, useState } from 'react';
import { toast } from 'sonner';
import { Plus, Pencil, Trash2, Search, X } from 'lucide-react';
import { cn, getAvatarColor, getInisial, formatTanggalShort } from '@/lib/utils';

export default function AdminPetugasPage() {
  const [data, setData] = useState<any[]>([]);
  const [loading, setLoading] = useState(true);
  const [search, setSearch] = useState('');
  const [showModal, setShowModal] = useState(false);
  const [editId, setEditId] = useState<string | null>(null);
  const [form, setForm] = useState({ name: '', email: '', password: '' });
  const [formLoading, setFormLoading] = useState(false);

  useEffect(() => { fetchData(); }, []);

  async function fetchData() {
    setLoading(true);
    try {
      const params = new URLSearchParams();
      if (search) params.set('search', search);
      const res = await fetch(`/api/admin/petugas?${params}`);
      if (!res.ok) throw new Error('Gagal memuat data');
      const json = await res.json();
      setData(json.data);
    } catch {
      toast.error('Gagal memuat data petugas');
    } finally {
      setLoading(false);
    }
  }

  function openAdd() {
    setEditId(null);
    setForm({ name: '', email: '', password: '' });
    setShowModal(true);
  }

  function openEdit(petugas: any) {
    setEditId(petugas.id);
    setForm({ name: petugas.name, email: petugas.email, password: '' });
    setShowModal(true);
  }

  async function handleSubmit(e: React.FormEvent) {
    e.preventDefault();
    setFormLoading(true);
    try {
      if (editId) {
        const body: any = {};
        if (form.name) body.name = form.name;
        if (form.email) body.email = form.email;
        if (form.password) body.password = form.password;
        const res = await fetch(`/api/admin/petugas/${editId}`, {
          method: 'PATCH',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify(body),
        });
        const json = await res.json();
        if (!res.ok) throw new Error(json.error);
        toast.success(json.message);
      } else {
        if (!form.name || !form.email || !form.password) {
          toast.error('Semua field wajib diisi');
          return;
        }
        const res = await fetch('/api/admin/petugas', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify(form),
        });
        const json = await res.json();
        if (!res.ok) throw new Error(json.error);
        toast.success(json.message);
      }
      setShowModal(false);
      fetchData();
    } catch (err: any) {
      toast.error(err.message || 'Gagal menyimpan');
    } finally {
      setFormLoading(false);
    }
  }

  async function handleDelete(id: string, name: string) {
    if (!confirm(`Nonaktifkan petugas "${name}"?`)) return;
    try {
      const res = await fetch(`/api/admin/petugas/${id}`, { method: 'DELETE' });
      const json = await res.json();
      if (!res.ok) throw new Error(json.error);
      toast.success(json.message);
      fetchData();
    } catch (err: any) {
      toast.error(err.message || 'Gagal menghapus');
    }
  }

  return (
    <div className="p-4 sm:p-6 space-y-6">
      <div className="flex items-center justify-between">
        <h1 className="text-2xl font-bold text-gray-900">Kelola Petugas</h1>
        <button onClick={openAdd} className="flex items-center gap-2 px-4 py-2 bg-green-600 text-white rounded-lg text-sm hover:bg-green-700">
          <Plus className="w-4 h-4" /> Tambah Petugas
        </button>
      </div>

      {/* Search */}
      <div className="flex gap-3">
        <div className="relative flex-1 max-w-md">
          <Search className="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" />
          <input
            type="text"
            placeholder="Cari nama atau email..."
            value={search}
            onChange={(e) => setSearch(e.target.value)}
            onKeyDown={(e) => e.key === 'Enter' && fetchData()}
            className="w-full pl-10 pr-4 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-green-500 focus:border-transparent"
          />
        </div>
        <button onClick={fetchData} className="px-4 py-2 bg-gray-100 rounded-lg text-sm hover:bg-gray-200">Cari</button>
      </div>

      {/* List */}
      {loading ? (
        <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
          {[...Array(6)].map((_, i) => <div key={i} className="bg-white rounded-xl p-5 shadow-sm animate-pulse h-32" />)}
        </div>
      ) : data.length === 0 ? (
        <div className="text-center py-12 text-gray-500">Belum ada petugas</div>
      ) : (
        <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
          {data.map((petugas) => (
            <div key={petugas.id} className="bg-white rounded-xl p-5 shadow-sm border border-gray-100">
              <div className="flex items-start gap-3 mb-3">
                <div className="w-10 h-10 rounded-full flex items-center justify-center text-white text-sm font-bold flex-shrink-0" style={{ backgroundColor: getAvatarColor(petugas.name) }}>
                  {getInisial(petugas.name)}
                </div>
                <div className="min-w-0 flex-1">
                  <p className="font-semibold text-gray-900 truncate">{petugas.name}</p>
                  <p className="text-sm text-gray-500 truncate">{petugas.email}</p>
                </div>
                <span className={cn('text-xs px-2 py-1 rounded-full', petugas.status_akun === 'aktif' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700')}>
                  {petugas.status_akun}
                </span>
              </div>
              <p className="text-xs text-gray-400 mb-3">Bergabung: {formatTanggalShort(petugas.created_at)}</p>
              <div className="flex gap-2">
                <button onClick={() => openEdit(petugas)} className="flex-1 flex items-center justify-center gap-1 px-3 py-2 border border-gray-200 rounded-lg text-sm hover:bg-gray-50">
                  <Pencil className="w-3.5 h-3.5" /> Edit
                </button>
                <button onClick={() => handleDelete(petugas.id, petugas.name)} className="flex-1 flex items-center justify-center gap-1 px-3 py-2 border border-red-200 text-red-600 rounded-lg text-sm hover:bg-red-50">
                  <Trash2 className="w-3.5 h-3.5" /> Nonaktifkan
                </button>
              </div>
            </div>
          ))}
        </div>
      )}

      {/* Modal */}
      {showModal && (
        <div className="fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-4">
          <div className="bg-white rounded-xl p-6 w-full max-w-md">
            <div className="flex items-center justify-between mb-4">
              <h3 className="text-lg font-semibold">{editId ? 'Edit Petugas' : 'Tambah Petugas'}</h3>
              <button onClick={() => setShowModal(false)}><X className="w-5 h-5 text-gray-400" /></button>
            </div>
            <form onSubmit={handleSubmit} className="space-y-4">
              <div>
                <label className="block text-sm font-medium text-gray-700 mb-1">Nama</label>
                <input type="text" value={form.name} onChange={(e) => setForm({ ...form, name: e.target.value })} className="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-green-500 focus:border-transparent" required />
              </div>
              <div>
                <label className="block text-sm font-medium text-gray-700 mb-1">Email</label>
                <input type="email" value={form.email} onChange={(e) => setForm({ ...form, email: e.target.value })} className="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-green-500 focus:border-transparent" required />
              </div>
              <div>
                <label className="block text-sm font-medium text-gray-700 mb-1">Password {editId && '(kosongkan jika tidak diubah)'}</label>
                <input type="password" value={form.password} onChange={(e) => setForm({ ...form, password: e.target.value })} className="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-green-500 focus:border-transparent" {...(editId ? {} : { required: true })} />
              </div>
              <div className="flex gap-2 pt-2">
                <button type="button" onClick={() => setShowModal(false)} className="flex-1 px-4 py-2 border border-gray-200 rounded-lg text-sm hover:bg-gray-50">Batal</button>
                <button type="submit" disabled={formLoading} className="flex-1 px-4 py-2 bg-green-600 text-white rounded-lg text-sm hover:bg-green-700 disabled:opacity-50">
                  {formLoading ? 'Menyimpan...' : editId ? 'Simpan' : 'Tambah'}
                </button>
              </div>
            </form>
          </div>
        </div>
      )}
    </div>
  );
}

'use client';

import { useState } from 'react';
import Link from 'next/link';
import { usePathname, useRouter } from 'next/navigation';
import { toast } from 'sonner';
import { cn, getAvatarUrl } from '@/lib/utils';
import {
  Leaf,
  LayoutDashboard,
  FileText,
  FilePlus,
  Wallet,
  Users,
  Shield,
  ClipboardCheck,
  Receipt,
  Tags,
  Bell,
  UserCircle,
  LogOut,
  Menu,
  X,
  ChevronRight,
} from 'lucide-react';
import type { SessionData } from '@/lib/auth';

interface SidebarProps {
  user: SessionData;
}

interface NavItem {
  label: string;
  href: string;
  icon: React.ElementType;
}

const wargaNav: NavItem[] = [
  { label: 'Dashboard', href: '/warga/dashboard', icon: LayoutDashboard },
  { label: 'Lapor Sampah', href: '/warga/lapor', icon: FilePlus },
  { label: 'Laporan Saya', href: '/warga/laporan-saya', icon: FileText },
  { label: 'Iuran', href: '/warga/iuran', icon: Wallet },
];

const petugasNav: NavItem[] = [
  { label: 'Dashboard', href: '/petugas/dashboard', icon: LayoutDashboard },
  { label: 'Laporan', href: '/petugas/laporan', icon: FileText },
  { label: 'Verifikasi Iuran', href: '/petugas/verifikasi-iuran', icon: ClipboardCheck },
];

const adminNav: NavItem[] = [
  { label: 'Dashboard', href: '/admin/dashboard', icon: LayoutDashboard },
  { label: 'Pengguna', href: '/admin/pengguna', icon: Users },
  { label: 'Petugas', href: '/admin/petugas', icon: Shield },
  { label: 'Laporan', href: '/admin/laporan', icon: FileText },
  { label: 'Iuran', href: '/admin/iuran', icon: Receipt },
  { label: 'Tarif', href: '/admin/tarif', icon: Tags },
];

const sharedNav: NavItem[] = [
  { label: 'Notifikasi', href: '/notifikasi', icon: Bell },
  { label: 'Profil', href: '/profile', icon: UserCircle },
];

function getNavItems(role: string): NavItem[] {
  switch (role) {
    case 'admin':
      return adminNav;
    case 'petugas':
      return petugasNav;
    case 'warga':
    default:
      return wargaNav;
  }
}

function getRoleBadge(role: string) {
  switch (role) {
    case 'admin':
      return 'bg-red-100 text-red-700 border-red-200';
    case 'petugas':
      return 'bg-blue-100 text-blue-700 border-blue-200';
    case 'warga':
    default:
      return 'bg-green-100 text-green-700 border-green-200';
  }
}

function getRoleLabel(role: string) {
  switch (role) {
    case 'admin':
      return 'Admin';
    case 'petugas':
      return 'Petugas';
    case 'warga':
    default:
      return 'Warga';
  }
}

export default function Sidebar({ user }: SidebarProps) {
  const pathname = usePathname();
  const router = useRouter();
  const [mobileOpen, setMobileOpen] = useState(false);
  const [loggingOut, setLoggingOut] = useState(false);

  const navItems = getNavItems(user.role);

  async function handleLogout() {
    setLoggingOut(true);
    try {
      await fetch('/api/auth/logout', { method: 'POST' });
      router.push('/');
      router.refresh();
    } catch {
      toast.error('Gagal logout');
    } finally {
      setLoggingOut(false);
    }
  }

  function isActive(href: string) {
    return pathname === href || pathname.startsWith(href + '/');
  }

  const sidebarContent = (
    <div className="flex flex-col h-full">
      {/* Logo */}
      <div className="p-5 border-b border-gray-100">
        <Link href={`/${user.role}/dashboard`} className="flex items-center gap-3">
          <div className="w-9 h-9 bg-green-600 rounded-xl flex items-center justify-center shadow-sm">
            <Leaf className="w-5 h-5 text-white" />
          </div>
          <div>
            <h1 className="text-sm font-bold text-gray-900">SIPLAS BBS</h1>
            <p className="text-[10px] text-gray-400 leading-tight">Pengelolaan Sampah</p>
          </div>
        </Link>
      </div>

      {/* User Info */}
      <div className="p-4 border-b border-gray-100">
        <div className="flex items-center gap-3">
          <img
            src={getAvatarUrl(user.name)}
            alt={user.name}
            className="w-10 h-10 rounded-full border-2 border-gray-100"
          />
          <div className="flex-1 min-w-0">
            <p className="text-sm font-semibold text-gray-900 truncate">{user.name}</p>
            <span
              className={cn(
                'inline-block mt-0.5 text-[10px] font-medium px-2 py-0.5 rounded-full border',
                getRoleBadge(user.role)
              )}
            >
              {getRoleLabel(user.role)}
            </span>
          </div>
        </div>
      </div>

      {/* Navigation */}
      <nav className="flex-1 overflow-y-auto p-3 space-y-0.5">
        <p className="px-3 py-2 text-[10px] font-semibold text-gray-400 uppercase tracking-wider">
          Menu Utama
        </p>
        {navItems.map((item) => {
          const active = isActive(item.href);
          return (
            <Link
              key={item.href}
              href={item.href}
              onClick={() => setMobileOpen(false)}
              className={cn(
                'flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all',
                active
                  ? 'bg-green-50 text-green-700'
                  : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900'
              )}
            >
              <item.icon
                className={cn(
                  'w-[18px] h-[18px] flex-shrink-0',
                  active ? 'text-green-600' : 'text-gray-400'
                )}
              />
              <span className="flex-1">{item.label}</span>
              {active && (
                <ChevronRight className="w-3.5 h-3.5 text-green-400" />
              )}
            </Link>
          );
        })}

        <div className="pt-3 mt-1 border-t border-gray-100">
          <p className="px-3 py-2 text-[10px] font-semibold text-gray-400 uppercase tracking-wider">
            Lainnya
          </p>
          {sharedNav.map((item) => {
            const active = isActive(item.href);
            return (
              <Link
                key={item.href}
                href={item.href}
                onClick={() => setMobileOpen(false)}
                className={cn(
                  'flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all',
                  active
                    ? 'bg-green-50 text-green-700'
                    : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900'
                )}
              >
                <item.icon
                  className={cn(
                    'w-[18px] h-[18px] flex-shrink-0',
                    active ? 'text-green-600' : 'text-gray-400'
                  )}
                />
                <span className="flex-1">{item.label}</span>
                {active && (
                  <ChevronRight className="w-3.5 h-3.5 text-green-400" />
                )}
              </Link>
            );
          })}
        </div>
      </nav>

      {/* Logout */}
      <div className="p-3 border-t border-gray-100">
        <button
          onClick={handleLogout}
          disabled={loggingOut}
          className="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium text-red-600 hover:bg-red-50 transition-colors w-full"
        >
          <LogOut className="w-[18px] h-[18px]" />
          <span>{loggingOut ? 'Keluar...' : 'Keluar'}</span>
        </button>
      </div>
    </div>
  );

  return (
    <>
      {/* Mobile Hamburger */}
      <button
        onClick={() => setMobileOpen(true)}
        className="lg:hidden fixed top-4 left-4 z-50 p-2 bg-white rounded-xl shadow-md border border-gray-200"
        aria-label="Buka menu"
      >
        <Menu className="w-5 h-5 text-gray-700" />
      </button>

      {/* Mobile Overlay */}
      {mobileOpen && (
        <div
          className="lg:hidden fixed inset-0 bg-black/30 z-40 backdrop-blur-sm"
          onClick={() => setMobileOpen(false)}
        />
      )}

      {/* Mobile Sidebar */}
      <aside
        className={cn(
          'lg:hidden fixed inset-y-0 left-0 z-50 w-72 bg-white border-r border-gray-200 shadow-xl transform transition-transform duration-300',
          mobileOpen ? 'translate-x-0' : '-translate-x-full'
        )}
      >
        <button
          onClick={() => setMobileOpen(false)}
          className="absolute top-4 right-4 p-1.5 rounded-lg hover:bg-gray-100"
          aria-label="Tutup menu"
        >
          <X className="w-4 h-4 text-gray-500" />
        </button>
        {sidebarContent}
      </aside>

      {/* Desktop Sidebar */}
      <aside className="hidden lg:flex lg:flex-shrink-0">
        <div className="w-64 bg-white border-r border-gray-200 h-screen sticky top-0 overflow-hidden">
          {sidebarContent}
        </div>
      </aside>
    </>
  );
}

import { NextResponse } from 'next/server';
import type { NextRequest } from 'next/server';
import { unsealData } from 'iron-session';

const protectedRoutes: Record<string, string[]> = {
  '/warga': ['warga'],
  '/petugas': ['petugas'],
  '/admin': ['admin'],
  '/profile': ['admin', 'petugas', 'warga'],
  '/notifikasi': ['admin', 'petugas', 'warga'],
};

const authRoutes = ['/auth/login', '/auth/register', '/auth/pending'];

export async function middleware(request: NextRequest) {
  const { pathname } = request.nextUrl;
  
  // Skip API routes, static files
  if (pathname.startsWith('/api') || pathname.startsWith('/_next') || pathname.startsWith('/favicon')) {
    return NextResponse.next();
  }

  const cookie = request.cookies.get('siplas-session');
  let session: any = null;

  if (cookie?.value) {
    try {
      session = await unsealData(cookie.value, {
        password: process.env.SESSION_SECRET || 'complex-password-at-least-32-characters-long',
        cookieName: 'siplas-session',
      });
    } catch {}
  }

  // Redirect authenticated users away from auth pages
  if (authRoutes.some(r => pathname.startsWith(r)) && session?.userId) {
    const dashboardUrl = getDashboardUrl(session.role);
    return NextResponse.redirect(new URL(dashboardUrl, request.url));
  }

  // Check protected routes
  for (const [route, roles] of Object.entries(protectedRoutes)) {
    if (pathname.startsWith(route)) {
      if (!session?.userId) {
        return NextResponse.redirect(new URL('/auth/login', request.url));
      }
      if (!roles.includes(session.role)) {
        const dashboardUrl = getDashboardUrl(session.role);
        return NextResponse.redirect(new URL(dashboardUrl, request.url));
      }
      break;
    }
  }

  // Root redirect
  if (pathname === '/' && session?.userId) {
    const dashboardUrl = getDashboardUrl(session.role);
    return NextResponse.redirect(new URL(dashboardUrl, request.url));
  }

  return NextResponse.next();
}

function getDashboardUrl(role: string): string {
  switch (role) {
    case 'admin': return '/admin/dashboard';
    case 'petugas': return '/petugas/dashboard';
    case 'warga': return '/warga/dashboard';
    default: return '/auth/login';
  }
}

export const config = {
  matcher: ['/((?!_next/static|_next/image|favicon.ico).*)'],
};

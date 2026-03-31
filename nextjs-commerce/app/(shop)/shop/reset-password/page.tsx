'use client';

import { ArrowLeftIcon, CheckCircleIcon, ExclamationCircleIcon, LockClosedIcon } from '@heroicons/react/24/outline';
import { resetPassword } from 'lib/api/auth';
import { UnknownError } from 'lib/api/types';
import { useAuth } from 'lib/hooks/useAuth';
import Link from 'next/link';
import { useRouter, useSearchParams } from 'next/navigation';
import { Suspense, useState } from 'react';

function ResetPasswordForm() {
  const { user } = useAuth();
  const router = useRouter();

  // Redirect if already logged in
  if (user) {
    router.replace('/shop');
    return null;
  }

  const searchParams = useSearchParams();
  const token = searchParams.get('token') || '';
  const email = searchParams.get('email') || '';

  const [password, setPassword] = useState('');
  const [passwordConfirmation, setPasswordConfirmation] = useState('');
  const [message, setMessage] = useState('');
  const [error, setError] = useState('');
  const [loading, setLoading] = useState(false);

  async function handleSubmit(e: React.FormEvent) {
    e.preventDefault();
    setLoading(true);
    setError('');
    setMessage('');

    if (password !== passwordConfirmation) {
      setError('Konfirmasi password tidak cocok.');
      return;
    }

    try {
      const result = await resetPassword(token, email, password, passwordConfirmation);
      setMessage(result.message || 'Password berhasil direset. Silakan login dengan password baru.');
    } catch (err: unknown) {
      const error = err as UnknownError;
      setError(error?.message || 'Gagal mereset password. Token mungkin sudah kadaluarsa.');
    } finally {
      setLoading(false);
    }
  }

  return (
    <div className="min-h-screen flex items-center justify-center bg-slate-50 relative overflow-hidden py-12 px-4 sm:px-6 lg:px-8">
      {/* Decorative Background Elements */}
      <div className="absolute top-0 right-0 w-[500px] h-[500px] bg-mitologi-gold/10 rounded-full blur-[120px] pointer-events-none translate-x-1/3 -translate-y-1/3" />
      <div className="absolute bottom-0 left-0 w-[600px] h-[600px] bg-mitologi-navy/5 rounded-full blur-[100px] pointer-events-none -translate-x-1/3 translate-y-1/3" />
      <div className="absolute inset-0 opacity-[0.03] bg-[radial-gradient(circle_at_2px_2px,_#0f172a_1px,_transparent_0)]" style={{ backgroundSize: "32px 32px" }} />

      <div className="w-full max-w-md p-8 sm:p-10 border border-slate-100 bg-white shadow-2xl shadow-mitologi-navy/10 rounded-3xl relative z-10">
        <div className="text-center mb-10 border-b border-slate-100 pb-8">
          <h1 className="text-3xl sm:text-4xl font-sans font-extrabold text-mitologi-navy tracking-tight mb-3">
            Reset Password
          </h1>
          <p className="text-sm font-sans font-medium text-slate-500">
            Masukkan password baru Anda
          </p>
        </div>

        <div>
          <form onSubmit={handleSubmit} className="space-y-6">
            {message && (
              <div className="p-4 bg-green-50 border border-green-200 rounded-xl flex items-start gap-3">
                <CheckCircleIcon className="w-5 h-5 text-green-700 mt-0.5 flex-shrink-0" />
                <div className="flex-1">
                  <p className="text-sm font-sans font-medium text-green-700">{message}</p>
                  <Link 
                    href="/shop/login"
                    className="text-sm font-sans font-bold text-mitologi-navy hover:text-mitologi-gold transition-colors mt-2 inline-block"
                  >
                    Login dengan Password Baru
                  </Link>
                </div>
              </div>
            )}
            
            {error && (
              <div className="p-4 bg-red-50 border border-red-200 rounded-xl flex items-start gap-3">
                <ExclamationCircleIcon className="w-5 h-5 text-red-700 mt-0.5 flex-shrink-0" />
                <p className="text-sm font-sans font-medium text-red-700">{error}</p>
              </div>
            )}

            <div>
              <label className="block text-sm font-sans font-bold text-slate-700 mb-2">
                Email
              </label>
              <input
                type="email"
                value={email}
                readOnly
                className="block w-full px-4 py-3 border border-slate-200 rounded-xl bg-slate-50 text-slate-500 text-sm cursor-not-allowed font-sans"
              />
            </div>

            <div>
              <label className="block text-sm font-sans font-bold text-slate-700 mb-2">
                Password Baru
              </label>
              <div className="relative group">
                <div className="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                  <LockClosedIcon className="h-5 w-5 text-slate-400 group-focus-within:text-mitologi-navy transition-colors" />
                </div>
                <input
                  type="password"
                  value={password}
                  onChange={(e) => setPassword(e.target.value)}
                  className="block w-full pl-11 pr-4 py-3.5 rounded-xl text-sm border border-slate-200 bg-white text-slate-900 placeholder:text-slate-400 hover:border-slate-300 focus:outline-none focus:ring-2 focus:ring-mitologi-navy/20 focus:border-mitologi-navy font-sans shadow-sm transition-all"
                  placeholder="••••••••"
                  required
                  minLength={8}
                />
              </div>
            </div>

            <div>
              <label className="block text-sm font-sans font-bold text-slate-700 mb-2">
                Konfirmasi Password
              </label>
              <div className="relative group">
                <div className="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                  <LockClosedIcon className="h-5 w-5 text-slate-400 group-focus-within:text-mitologi-navy transition-colors" />
                </div>
                <input
                  type="password"
                  value={passwordConfirmation}
                  onChange={(e) => setPasswordConfirmation(e.target.value)}
                  className="block w-full pl-11 pr-4 py-3.5 rounded-xl text-sm border border-slate-200 bg-white text-slate-900 placeholder:text-slate-400 hover:border-slate-300 focus:outline-none focus:ring-2 focus:ring-mitologi-navy/20 focus:border-mitologi-navy font-sans shadow-sm transition-all"
                  placeholder="••••••••"
                  required
                />
              </div>
            </div>

            <button
              type="submit"
              disabled={loading}
              className="w-full flex items-center justify-center p-4 rounded-full font-sans tracking-wide bg-gradient-to-r from-mitologi-navy to-mitologi-navy-light text-white shadow-lg shadow-mitologi-navy/20 hover:shadow-mitologi-gold/20 transition-all text-base disabled:opacity-50 font-semibold"
            >
              {loading ? 'Mereset...' : 'Reset Password'}
            </button>

            <div className="text-center pt-2">
              <Link 
                href="/shop/login" 
                className="inline-flex items-center gap-2 text-sm font-sans font-semibold text-mitologi-navy hover:text-mitologi-gold transition-colors"
              >
                <ArrowLeftIcon className="w-4 h-4" />
                <span>Kembali ke Login</span>
              </Link>
            </div>
          </form>
        </div>
        
        <p className="text-center mt-10 text-xs font-sans font-medium text-slate-400 pt-8 border-t border-slate-100">
          &copy; {new Date().getFullYear()} Mitologi Clothing. All rights reserved.
        </p>
      </div>
    </div>
  );
}

export default function ResetPasswordPage() {
  return (
    <Suspense fallback={
      <div className="min-h-screen flex items-center justify-center bg-slate-50">
        <div className="flex justify-center py-10"><div className="animate-spin rounded-full h-8 w-8 border-2 border-mitologi-navy border-t-transparent"></div></div>
      </div>
    }>
      <ResetPasswordForm />
    </Suspense>
  );
}

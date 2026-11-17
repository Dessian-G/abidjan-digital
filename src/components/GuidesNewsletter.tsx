'use client';

import { useState, FormEvent } from 'react';
import { CheckCircle, AlertCircle, MessageCircle } from 'lucide-react';
import { useLanguage } from '@/context/LanguageContext';
import { WHATSAPP_NUMBER } from '@/lib/apps.config';

// Inscription « prochains guides » : envoie l'email via Web3Forms
// (même clé que le formulaire de contact) vers abidjandigital.ia@gmail.com.
export default function GuidesNewsletter() {
  const { lang, t } = useLanguage();
  const [email, setEmail] = useState('');
  const [honeypot, setHoneypot] = useState('');
  const [status, setStatus] = useState<'idle' | 'loading' | 'success' | 'error'>('idle');

  const whatsappHref = `https://wa.me/${WHATSAPP_NUMBER}?text=${encodeURIComponent(t.guides.whatsappMessage)}`;

  const handleSubmit = async (e: FormEvent) => {
    e.preventDefault();
    if (honeypot) return;
    setStatus('loading');
    try {
      const res = await fetch('https://api.web3forms.com/submit', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', Accept: 'application/json' },
        body: JSON.stringify({
          access_key: process.env.NEXT_PUBLIC_WEB3FORMS_KEY ?? '',
          subject: 'Nouvelle inscription aux guides gratuits — Abidjan Digital.ia',
          from_name: 'Page Guides gratuits',
          email,
          message: `Inscription aux guides gratuits (langue : ${lang.toUpperCase()}).`,
        }),
      });
      const data = await res.json();
      if (data.success) {
        setStatus('success');
        setEmail('');
      } else {
        setStatus('error');
      }
    } catch {
      setStatus('error');
    }
  };

  return (
    <section id="newsletter" className="py-12 sm:py-16 px-4 sm:px-6 scroll-mt-20" style={{ backgroundColor: '#0E2A63' }}>
      <div className="max-w-5xl mx-auto grid md:grid-cols-2 gap-8 md:gap-12 items-center text-white">
        <div>
          <h2 className="text-2xl sm:text-3xl font-extrabold mb-3">{t.guides.newsletterTitle}</h2>
          <p className="text-blue-200 mb-6 text-sm sm:text-base">{t.guides.newsletterText}</p>
          <p className="text-xs font-semibold uppercase tracking-wide text-orange-400 mb-3">
            {t.guides.newsletterItemsHeading}
          </p>
          <ol className="space-y-2">
            {t.guides.newsletterItems.map((item, i) => (
              <li key={item} className="flex items-center gap-3 text-sm sm:text-base">
                <span
                  className="w-7 h-7 rounded-full flex items-center justify-center text-xs font-bold shrink-0"
                  style={{ backgroundColor: '#F4791F' }}
                >
                  {i + 1}
                </span>
                {item}
              </li>
            ))}
          </ol>
        </div>

        <div className="bg-white rounded-2xl p-5 sm:p-7 text-gray-800 shadow-lg">
          {status === 'success' ? (
            <div className="flex flex-col items-center text-center py-6">
              <CheckCircle size={44} className="mb-3" style={{ color: '#4FB52B' }} />
              <p className="font-semibold" style={{ color: '#0E2A63' }}>{t.guides.success}</p>
            </div>
          ) : (
            <form onSubmit={handleSubmit}>
              <label htmlFor="guides-email" className="block text-sm font-semibold mb-2" style={{ color: '#0E2A63' }}>
                {t.guides.emailLabel}
              </label>
              <div className="flex flex-col sm:flex-row gap-2">
                <input
                  id="guides-email"
                  type="email"
                  required
                  autoComplete="email"
                  value={email}
                  onChange={e => setEmail(e.target.value)}
                  placeholder={t.guides.emailPlaceholder}
                  className="flex-1 min-w-0 px-4 py-3 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-orange-300"
                />
                {/* Champ piège anti-spam, invisible pour les humains */}
                <input
                  type="text"
                  name="botcheck"
                  tabIndex={-1}
                  autoComplete="off"
                  value={honeypot}
                  onChange={e => setHoneypot(e.target.value)}
                  className="hidden"
                  aria-hidden="true"
                />
                <button
                  type="submit"
                  disabled={status === 'loading'}
                  className="px-6 py-3 rounded-xl font-bold text-white text-sm transition-opacity disabled:opacity-60 whitespace-nowrap"
                  style={{ backgroundColor: '#F4791F' }}
                >
                  {status === 'loading' ? t.guides.sending : t.guides.join}
                </button>
              </div>
              {status === 'error' && (
                <p className="mt-3 flex items-center gap-2 text-sm text-red-600">
                  <AlertCircle size={16} /> {t.guides.error}
                </p>
              )}
              <p className="mt-3 text-xs text-gray-400">{t.guides.microcopy}</p>
            </form>
          )}

          <div className="mt-5 pt-5 border-t border-gray-100 text-center">
            <p className="text-xs text-gray-500 mb-2">{t.guides.orWhatsapp}</p>
            <a
              href={whatsappHref}
              target="_blank"
              rel="noopener noreferrer"
              className="inline-flex items-center gap-2 text-sm font-semibold hover:underline"
              style={{ color: '#4FB52B' }}
            >
              <MessageCircle size={16} /> {t.guides.whatsappCta}
            </a>
          </div>
        </div>
      </div>
    </section>
  );
}

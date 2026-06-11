'use client';

import { useState, FormEvent } from 'react';
import { useSearchParams } from 'next/navigation';
import { CheckCircle, AlertCircle } from 'lucide-react';

const SERVICES = [
  { value: '', label: 'Choisissez un service…' },
  { value: 'Formation digitale & IA', label: 'Formation digitale & IA' },
  { value: 'Infographie', label: "Création d'infographie" },
  { value: 'Site web', label: 'Création de site web' },
  { value: 'Autre', label: 'Autre / Je ne sais pas encore' },
];

const SERVICE_MAP: Record<string, string> = {
  formations: 'Formation digitale & IA',
  infographies: 'Infographie',
  'sites-web': 'Site web',
};

export default function ContactForm() {
  const searchParams = useSearchParams();
  const preSelected = SERVICE_MAP[searchParams.get('service') ?? ''] ?? '';

  const [form, setForm] = useState({
    name: '',
    email: '',
    phone: '',
    service: preSelected,
    message: '',
    honeypot: '',
  });
  const [status, setStatus] = useState<'idle' | 'loading' | 'success' | 'error'>('idle');

  const handleChange = (e: React.ChangeEvent<HTMLInputElement | HTMLSelectElement | HTMLTextAreaElement>) => {
    setForm(prev => ({ ...prev, [e.target.name]: e.target.value }));
  };

  const handleSubmit = async (e: FormEvent) => {
    e.preventDefault();
    if (form.honeypot) return;
    setStatus('loading');

    const accessKey = process.env.NEXT_PUBLIC_WEB3FORMS_KEY ?? '';

    try {
      const res = await fetch('https://api.web3forms.com/submit', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', Accept: 'application/json' },
        body: JSON.stringify({
          access_key: accessKey,
          subject: `Nouvelle demande — ${form.service || 'Non précisé'} — Abidjan Digital.ia`,
          from_name: form.name,
          email: form.email,
          phone: form.phone,
          service: form.service,
          message: form.message,
        }),
      });
      const data = await res.json();
      if (data.success) {
        setStatus('success');
        setForm({ name: '', email: '', phone: '', service: '', message: '', honeypot: '' });
      } else {
        setStatus('error');
      }
    } catch {
      setStatus('error');
    }
  };

  if (status === 'success') {
    return (
      <div className="flex flex-col items-center justify-center text-center py-16 px-4 rounded-2xl border border-green-100 bg-green-50">
        <CheckCircle size={48} className="mb-4" style={{ color: '#4FB52B' }} />
        <h3 className="text-xl font-bold mb-2" style={{ color: '#0E2A63' }}>Demande envoyée !</h3>
        <p className="text-gray-600 max-w-sm">
          Merci, votre demande a bien été envoyée. Nous vous répondrons rapidement à l'adresse indiquée.
        </p>
      </div>
    );
  }

  return (
    <form onSubmit={handleSubmit} className="space-y-5" noValidate>
      {/* Honeypot anti-spam */}
      <input
        type="text"
        name="honeypot"
        value={form.honeypot}
        onChange={handleChange}
        className="hidden"
        tabIndex={-1}
        autoComplete="off"
        aria-hidden="true"
      />

      <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
        {/* Nom */}
        <div>
          <label htmlFor="name" className="block text-sm font-semibold mb-1.5" style={{ color: '#0E2A63' }}>
            Nom complet <span className="text-red-500">*</span>
          </label>
          <input
            id="name"
            name="name"
            type="text"
            required
            value={form.name}
            onChange={handleChange}
            placeholder="Marie Koné"
            className="w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm focus:outline-none focus:border-orange-400 transition-colors"
          />
        </div>

        {/* Email */}
        <div>
          <label htmlFor="email" className="block text-sm font-semibold mb-1.5" style={{ color: '#0E2A63' }}>
            Adresse email <span className="text-red-500">*</span>
          </label>
          <input
            id="email"
            name="email"
            type="email"
            required
            value={form.email}
            onChange={handleChange}
            placeholder="marie@exemple.com"
            className="w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm focus:outline-none focus:border-orange-400 transition-colors"
          />
        </div>
      </div>

      <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
        {/* Téléphone */}
        <div>
          <label htmlFor="phone" className="block text-sm font-semibold mb-1.5" style={{ color: '#0E2A63' }}>
            Téléphone / WhatsApp
          </label>
          <input
            id="phone"
            name="phone"
            type="tel"
            value={form.phone}
            onChange={handleChange}
            placeholder="+225 07 00 00 00 00"
            className="w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm focus:outline-none focus:border-orange-400 transition-colors"
          />
        </div>

        {/* Service */}
        <div>
          <label htmlFor="service" className="block text-sm font-semibold mb-1.5" style={{ color: '#0E2A63' }}>
            Service souhaité <span className="text-red-500">*</span>
          </label>
          <select
            id="service"
            name="service"
            required
            value={form.service}
            onChange={handleChange}
            className="w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm focus:outline-none focus:border-orange-400 transition-colors bg-white"
          >
            {SERVICES.map(({ value, label }) => (
              <option key={value} value={value} disabled={value === ''}>
                {label}
              </option>
            ))}
          </select>
        </div>
      </div>

      {/* Message */}
      <div>
        <label htmlFor="message" className="block text-sm font-semibold mb-1.5" style={{ color: '#0E2A63' }}>
          Description de votre besoin <span className="text-red-500">*</span>
        </label>
        <textarea
          id="message"
          name="message"
          required
          rows={5}
          value={form.message}
          onChange={handleChange}
          placeholder="Décrivez votre projet, vos objectifs, vos délais… Plus vous êtes précis, mieux nous pourrons vous aider."
          className="w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm focus:outline-none focus:border-orange-400 transition-colors resize-none"
        />
      </div>

      {status === 'error' && (
        <div className="flex items-center gap-2 text-sm text-red-600 bg-red-50 rounded-xl px-4 py-3">
          <AlertCircle size={16} />
          Une erreur est survenue. Veuillez réessayer ou écrire à abidjandigitalia@gmail.com.
        </div>
      )}

      <button
        type="submit"
        disabled={status === 'loading'}
        className="w-full sm:w-auto px-8 py-3 rounded-xl font-bold text-white text-sm shadow-md hover:shadow-lg transition-all hover:scale-105 disabled:opacity-60 disabled:cursor-not-allowed"
        style={{ backgroundColor: '#F4791F' }}
      >
        {status === 'loading' ? 'Envoi en cours…' : 'Envoyer ma demande'}
      </button>

      <p className="text-xs text-gray-400">
        Les champs marqués <span className="text-red-500">*</span> sont obligatoires.
      </p>
    </form>
  );
}

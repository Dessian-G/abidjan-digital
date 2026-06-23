import type { Metadata } from 'next';
import { Inter } from 'next/font/google';
import './globals.css';
import Header from '@/components/Header';
import Footer from '@/components/Footer';
import ChatbotWidget from '@/components/ChatbotWidget';

const inter = Inter({ subsets: ['latin'] });

export const metadata: Metadata = {
  title: 'Abidjan Digital.ia — Studio Web & IA | Fort Worth & Abidjan',
  description:
    "Abidjan Digital.ia est votre partenaire digital : formations IA, création d'infographies et de sites web. Présent à Fort Worth (Texas) et Abidjan (Côte d'Ivoire).",
  keywords: "agence digitale, intelligence artificielle, formation IA, site web, infographie, Abidjan, Fort Worth, Côte d'Ivoire",
  openGraph: {
    title: 'Abidjan Digital.ia — Apprenez. Automatisez. Réussissez.',
    description: 'Studio web & IA pour entrepreneurs francophones — Fort Worth & Abidjan.',
    type: 'website',
    locale: 'fr_FR',
  },
};

export default function RootLayout({ children }: { children: React.ReactNode }) {
  return (
    <html lang="fr">
      <head>
        <script
          type="application/ld+json"
          dangerouslySetInnerHTML={{
            __html: JSON.stringify({
              '@context': 'https://schema.org',
              '@type': 'LocalBusiness',
              name: 'Abidjan Digital.ia',
              description: 'Studio web & intelligence artificielle',
              email: 'abidjandigitail.ia@gmail.com',
              address: [
                {
                  '@type': 'PostalAddress',
                  addressLocality: 'Fort Worth',
                  addressRegion: 'TX',
                  addressCountry: 'US',
                },
                {
                  '@type': 'PostalAddress',
                  addressLocality: 'Abidjan',
                  addressRegion: 'Cocody',
                  addressCountry: 'CI',
                },
              ],
            }),
          }}
        />
      </head>
      <body className={inter.className}>
        <Header />
        <main>{children}</main>
        <Footer />
        <ChatbotWidget />
      </body>
    </html>
  );
}

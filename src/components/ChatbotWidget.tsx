'use client';

import { useState, useRef, useEffect, ReactNode } from 'react';
import Link from 'next/link';
import { MessageCircle, X, Send, Bot } from 'lucide-react';
import { useLanguage } from '@/context/LanguageContext';

interface Message {
  role: 'user' | 'assistant';
  content: string;
}

// Liens externes, emails et pages du site (/guides/…, /contact?service=…) rendus cliquables.
const LINK_RE = /(https?:\/\/[^\s<>()]+)|([\w.+-]+@[\w-]+\.[\w.-]+)|((?<=^|[\s(«"'])\/[a-z0-9][\w\-/?=&#.]*)/gi;
const TRAILING_PUNCT = /[.,;:!?)»"']+$/;

function renderWithLinks(text: string): ReactNode[] {
  const nodes: ReactNode[] = [];
  let last = 0;
  for (const match of text.matchAll(LINK_RE)) {
    const raw = match[0];
    const start = match.index ?? 0;
    const href = raw.replace(TRAILING_PUNCT, '');
    if (!href) continue;
    nodes.push(text.slice(last, start));
    const className = 'underline font-medium break-all';
    const style = { color: '#F4791F' };
    if (match[1]) {
      nodes.push(<a key={start} href={href} target="_blank" rel="noopener noreferrer" className={className} style={style}>{href}</a>);
    } else if (match[2]) {
      nodes.push(<a key={start} href={`mailto:${href}`} className={className} style={style}>{href}</a>);
    } else {
      nodes.push(<Link key={start} href={href} className={className} style={style}>{href}</Link>);
    }
    last = start + href.length;
  }
  nodes.push(text.slice(last));
  return nodes;
}

// Si le modèle écrit malgré tout du markdown : **gras** affiché en gras, puces « * » en tirets.
function renderMessage(text: string): ReactNode[] {
  const clean = text.replace(/^(\s*)\* /gm, '$1- ').replace(/^#{1,6}\s+/gm, '');
  return clean.split(/\*\*([^*\n]+?)\*\*/g).map((part, i) =>
    i % 2 === 1 ? (
      <strong key={i} className="font-semibold">{renderWithLinks(part)}</strong>
    ) : (
      <span key={i}>{renderWithLinks(part.replace(/\*\*/g, ''))}</span>
    )
  );
}

export default function ChatbotWidget() {
  const { lang, t } = useLanguage();
  const [open, setOpen] = useState(false);
  const [messages, setMessages] = useState<Message[]>([
    { role: 'assistant', content: t.chatbot.welcome },
  ]);
  const [input, setInput] = useState('');
  const [loading, setLoading] = useState(false);
  const bottomRef = useRef<HTMLDivElement>(null);

  useEffect(() => {
    bottomRef.current?.scrollIntoView({ behavior: 'smooth' });
  }, [messages, open]);

  // Keep the untouched welcome message in sync with the current language.
  useEffect(() => {
    // eslint-disable-next-line react-hooks/set-state-in-effect
    setMessages(prev =>
      prev.length === 1 && prev[0].role === 'assistant'
        ? [{ role: 'assistant', content: t.chatbot.welcome }]
        : prev
    );
  }, [t.chatbot.welcome]);

  const send = async () => {
    const text = input.trim();
    if (!text || loading) return;

    const newMessages: Message[] = [...messages, { role: 'user', content: text }];
    setMessages(newMessages);
    setInput('');
    setLoading(true);

    try {
      const res = await fetch('/api/chat', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ messages: newMessages, lang }),
      });
      const data = await res.json();
      const reply = typeof data?.reply === 'string' && data.reply ? data.reply : t.chatbot.errorMessage;
      setMessages([...newMessages, { role: 'assistant', content: reply }]);
    } catch {
      setMessages([
        ...newMessages,
        { role: 'assistant', content: t.chatbot.errorMessage },
      ]);
    } finally {
      setLoading(false);
    }
  };

  return (
    <>
      {/* Floating button */}
      <button
        onClick={() => setOpen(!open)}
        className="fixed bottom-5 right-5 z-50 w-14 h-14 rounded-full text-white shadow-xl flex items-center justify-center transition-transform hover:scale-110 focus:outline-none"
        style={{ backgroundColor: '#F4791F' }}
        aria-label={open ? t.chatbot.closeLabel : t.chatbot.openLabel}
      >
        {open ? <X size={24} /> : <MessageCircle size={24} />}
      </button>

      {/* Chat window */}
      {open && (
        <div className="fixed bottom-20 sm:bottom-24 right-3 sm:right-5 z-50 w-[92vw] sm:w-[90vw] max-w-sm bg-white rounded-2xl shadow-2xl flex flex-col overflow-hidden border border-gray-100"
          style={{ height: 'min(480px, 70vh)' }}>
          {/* Header */}
          <div className="flex items-center gap-3 px-4 py-3 text-white" style={{ backgroundColor: '#0E2A63' }}>
            <div className="w-9 h-9 rounded-full flex items-center justify-center" style={{ backgroundColor: '#F4791F' }}>
              <Bot size={18} />
            </div>
            <div>
              <p className="font-semibold text-sm leading-none">Adia</p>
              <p className="text-xs text-blue-200 mt-0.5">{t.chatbot.subtitle}</p>
            </div>
          </div>

          {/* Messages */}
          <div className="flex-1 overflow-y-auto px-4 py-3 space-y-3 bg-gray-50">
            {messages.map((m, i) => (
              <div key={i} className={`flex ${m.role === 'user' ? 'justify-end' : 'justify-start'}`}>
                <div
                  className={`max-w-[85%] px-3 py-2 rounded-2xl text-sm leading-relaxed whitespace-pre-wrap break-words ${
                    m.role === 'user'
                      ? 'text-white rounded-br-sm'
                      : 'text-gray-800 bg-white border border-gray-200 rounded-bl-sm shadow-sm'
                  }`}
                  style={m.role === 'user' ? { backgroundColor: '#0E2A63' } : {}}
                >
                  {m.role === 'assistant' ? renderMessage(m.content) : m.content}
                </div>
              </div>
            ))}
            {loading && (
              <div className="flex justify-start">
                <div className="bg-white border border-gray-200 rounded-2xl rounded-bl-sm px-4 py-2 text-sm text-gray-400 shadow-sm">
                  {t.chatbot.typing}
                </div>
              </div>
            )}
            <div ref={bottomRef} />
          </div>

          {/* Input */}
          <div className="px-3 py-3 border-t border-gray-100 bg-white flex gap-2">
            <input
              type="text"
              value={input}
              onChange={e => setInput(e.target.value)}
              onKeyDown={e => e.key === 'Enter' && send()}
              placeholder={t.chatbot.placeholder}
              className="flex-1 text-sm border border-gray-200 rounded-xl px-3 py-2 focus:outline-none focus:border-orange-400"
            />
            <button
              onClick={send}
              disabled={!input.trim() || loading}
              className="w-9 h-9 rounded-xl flex items-center justify-center text-white transition-opacity disabled:opacity-40"
              style={{ backgroundColor: '#F4791F' }}
              aria-label={t.chatbot.sendLabel}
            >
              <Send size={16} />
            </button>
          </div>
        </div>
      )}
    </>
  );
}

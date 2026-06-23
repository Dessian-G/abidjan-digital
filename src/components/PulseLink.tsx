'use client';

import { useState, useEffect } from 'react';
import Link from 'next/link';

interface PulseLinkProps {
  href: string;
  children: React.ReactNode;
  className?: string;
  style?: React.CSSProperties;
  pulseInterval?: number;
}

export default function PulseLink({ href, children, className = '', style, pulseInterval = 5000 }: PulseLinkProps) {
  const [isPulsing, setIsPulsing] = useState(false);

  useEffect(() => {
    const interval = setInterval(() => {
      setIsPulsing(true);
      setTimeout(() => setIsPulsing(false), 1000);
    }, pulseInterval);
    return () => clearInterval(interval);
  }, [pulseInterval]);

  return (
    <Link
      href={href}
      className={`${className} ${isPulsing ? 'animate-cta-pulse' : ''}`}
      style={style}
    >
      {children}
    </Link>
  );
}

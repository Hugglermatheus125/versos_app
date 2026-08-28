"use client";

import Link from "next/link";
import { useEffect, useState } from "react";

export default function AccountModal() {
  const [isOpen, setIsOpen] = useState(false);

  useEffect(() => {
    if (!isOpen) return;
    const closeWithEscape = (event: KeyboardEvent) => { if (event.key === "Escape") setIsOpen(false); };
    document.addEventListener("keydown", closeWithEscape);
    return () => document.removeEventListener("keydown", closeWithEscape);
  }, [isOpen]);

  return <><button className="profile-button" type="button" onClick={() => setIsOpen(true)} aria-label="Abrir menu do perfil de Marina" title="Menu do perfil"><span className="profile-name">Menu</span><span className="profile-avatar">M</span></button>{isOpen && <div className="account-overlay" role="presentation" onMouseDown={() => setIsOpen(false)}><section className="account-modal" role="dialog" aria-modal="true" aria-labelledby="account-title" onMouseDown={(event) => event.stopPropagation()}><div className="account-modal-heading"><div><p className="eyebrow text-coral">minha conta</p><h2 id="account-title">Olá, Marina</h2></div><button className="account-close" type="button" onClick={() => setIsOpen(false)} aria-label="Fechar perfil">×</button></div><div className="account-summary"><span className="profile-avatar profile-avatar-large">M</span><div><strong>Marina Alves</strong><small>conta gratuita</small></div></div><Link className="account-option premium-option" href="/configuracoes#premium" onClick={() => setIsOpen(false)}><span><b>✦ Tornar-se Premium</b><small>Tenha uma experiência sem interrupções.</small></span><span>→</span></Link><Link className="account-option" href="/configuracoes" onClick={() => setIsOpen(false)}><span><b>⚙ Configurações</b><small>Preferências, aparência e lembretes.</small></span><span>→</span></Link><button className="account-logout" type="button" onClick={() => setIsOpen(false)}>Sair da conta</button></section></div>}</>;
}
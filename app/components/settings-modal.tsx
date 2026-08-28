"use client";

import { useEffect, useState } from "react";

export default function SettingsModal() {
  const [isOpen, setIsOpen] = useState(false);
  const [focusMode, setFocusMode] = useState(false);
  const [notifications, setNotifications] = useState(true);

  useEffect(() => {
    if (!isOpen) return;
    const handleKeyDown = (event: KeyboardEvent) => {
      if (event.key === "Escape") setIsOpen(false);
    };
    document.addEventListener("keydown", handleKeyDown);
    return () => document.removeEventListener("keydown", handleKeyDown);
  }, [isOpen]);

  return (
    <>
      <button className="icon-button" type="button" onClick={() => setIsOpen(true)} aria-label="Abrir configurações" title="Configurações">
        <span aria-hidden="true">⚙</span>
      </button>
      {isOpen && (
        <div className="modal-backdrop" role="presentation" onMouseDown={() => setIsOpen(false)}>
          <section className="settings-modal" role="dialog" aria-modal="true" aria-labelledby="settings-title" onMouseDown={(event) => event.stopPropagation()}>
            <div className="flex items-start justify-between gap-6 border-b border-line pb-6">
              <div><p className="eyebrow text-coral">preferências</p><h2 id="settings-title" className="mt-2 font-display text-3xl tracking-tight">Configurações</h2></div>
              <button className="close-button" type="button" onClick={() => setIsOpen(false)} aria-label="Fechar configurações">×</button>
            </div>
            <div className="divide-y divide-line">
              <label className="setting-row"><span><strong>Modo foco</strong><small>Uma interface mais silenciosa para escrever.</small></span><input type="checkbox" checked={focusMode} onChange={(event) => setFocusMode(event.target.checked)} /></label>
              <label className="setting-row"><span><strong>Lembretes</strong><small>Receber um lembrete para voltar aos seus versos.</small></span><input type="checkbox" checked={notifications} onChange={(event) => setNotifications(event.target.checked)} /></label>
              <label className="setting-row"><span><strong>Idioma</strong><small>Idioma principal da interface.</small></span><select defaultValue="pt"><option value="pt">Português</option><option value="en">English</option></select></label>
            </div>
            <button className="button-primary mt-7 w-full" type="button" onClick={() => setIsOpen(false)}>Concluir <span aria-hidden="true">↗</span></button>
          </section>
        </div>
      )}
    </>
  );
}
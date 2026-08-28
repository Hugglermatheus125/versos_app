"use client";

import { useState } from "react";
import Link from "next/link";
import AccountModal from "./account-modal";

const poems = [
  { title: "Canção do exílio", author: "Gonçalves Dias", year: "1843", lines: ["Minha terra tem palmeiras,", "Onde canta o Sabiá;", "As aves, que aqui gorjeiam,", "Não gorjeiam como lá."], continuation: ["Nosso céu tem mais estrelas,", "Nossas várzeas têm mais flores,", "Nossos bosques têm mais vida,", "Nossa vida mais amores."] },
  { title: "Meus oito anos", author: "Casimiro de Abreu", year: "1859", lines: ["Oh! que saudades que tenho", "Da aurora da minha vida,", "Da minha infância querida", "Que os anos não trazem mais!"], continuation: ["Que amor, que sonhos, que flores,", "Naquelas tardes fagueiras", "À sombra das bananeiras,", "Debaixo dos laranjais!"] },
];

export default function PoemReader() {
  const [currentPoem, setCurrentPoem] = useState(0);
  const [isFavorite, setIsFavorite] = useState(false);
  const [isLiked, setIsLiked] = useState(false);
  const [isShared, setIsShared] = useState(false);
  const poem = poems[currentPoem];
  const changePoem = (index: number) => { setCurrentPoem(index); setIsFavorite(false); setIsLiked(false); setIsShared(false); };
  async function sharePoem() {
    if (navigator.share) await navigator.share({ title: poem.title, text: poem.lines.join("\n") });
    setIsShared(true);
  }

  return (
    <main className="reader-shell">
      <header className="reader-header"><Link className="reader-brand" href="/" aria-label="Verso, início"><span className="brand-mark brand-mark-caramel">V</span><span><strong>Verso</strong><small>todos os poemas</small></span></Link><div className="reader-actions"><Link className="premium-header-link" href="/configuracoes#premium">✦ Premium</Link><AccountModal /></div></header>
      <section className="poem-stage" aria-live="polite"><div className="poem-meta"><span>{poem.author} · {poem.year}</span><i /></div><article className="poem-copy"><p className="poem-label">{poem.title}</p><div className="poem-stanza">{poem.lines.map((line) => <p key={line}>{line}</p>)}</div><div className="poem-stanza">{poem.continuation.map((line) => <p key={line}>{line}</p>)}</div><div className="poem-stanza poem-stanza-soft"><p>Em cismar, sozinho, à noite,</p><p>Mais prazer encontro eu lá;</p></div><div className="poem-actions"><button className={isLiked ? "action-active" : ""} type="button" onClick={() => setIsLiked(!isLiked)} aria-label="Curtir poema">{isLiked ? "♥" : "♡"} <span>{isLiked ? "Curtido" : "Curtir"}</span></button><button className={isFavorite ? "action-active" : ""} type="button" onClick={() => setIsFavorite(!isFavorite)} aria-label="Favoritar em coleções">▣ <span>Favoritar</span></button><button className={isShared ? "action-active" : ""} type="button" onClick={sharePoem} aria-label="Compartilhar poema">↗ <span>{isShared ? "Compartilhado" : "Compartilhar"}</span></button></div></article><button className="next-poem" type="button" onClick={() => changePoem((currentPoem + 1) % poems.length)} aria-label="Próximo poema">↓</button></section>
      <div className="poem-progress" aria-label={`Poema ${currentPoem + 1} de ${poems.length}`}><span>{String(currentPoem + 1).padStart(2, "0")}</span><b>—</b><span>{String(poems.length).padStart(2, "0")}</span><div className="progress-dots">{poems.map((item, index) => <button key={item.title} type="button" className={index === currentPoem ? "active" : ""} onClick={() => changePoem(index)} aria-label={`Ler ${item.title}`} />)}</div></div>
    </main>
  );
}
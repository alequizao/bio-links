<?php
declare(strict_types=1);

$adminTitle = ($isNew ? 'Nova bio' : 'Editar bio') . ' · Painel';
$csrf = csrf_token();

// Mapa de ícones para o JavaScript (nome => svg)
$iconsJs = [];
foreach (icon_list() as $n) {
    $iconsJs[$n] = icon($n, 22, 1.7);
}
require __DIR__ . '/admin_head.php';
?>
<style>
  .editor{display:grid;grid-template-columns:1fr 420px;gap:0;height:calc(100vh - 60px)}
  @media(max-width:980px){.editor{grid-template-columns:1fr;height:auto}.preview-pane{position:static!important;height:auto!important}}
  .form-pane{overflow-y:auto;padding:22px 26px 80px}
  .preview-pane{background:#e9eef6;border-left:1px solid var(--line);position:sticky;top:60px;height:calc(100vh - 60px);
    display:flex;flex-direction:column;align-items:center;justify-content:center;padding:22px}
  .phone{width:340px;max-width:100%;height:660px;background:#fff;border-radius:30px;overflow:hidden;
    box-shadow:0 16px 50px rgba(10,20,50,.28);border:8px solid #0e1320}
  .phone iframe{width:100%;height:100%;border:0;display:block}
  .preview-pane .plink{margin-top:14px;font-size:13px}
  .sec{margin-bottom:22px;border:1px solid var(--line);border-radius:12px;background:#fff;overflow:hidden}
  .sec>h3{font-size:14px;padding:14px 16px;background:#f3f6fc;border-bottom:1px solid var(--line);cursor:pointer;display:flex;align-items:center;justify-content:between;gap:8px;user-select:none}
  .sec>h3 .chev{margin-left:auto;transition:.2s;color:var(--mut)}
  .sec.collapsed>h3 .chev{transform:rotate(-90deg)}
  .sec .sec-body{padding:16px}
  .sec.collapsed .sec-body{display:none}
  .row2{display:grid;grid-template-columns:1fr 1fr;gap:12px}
  .row3{display:grid;grid-template-columns:repeat(3,1fr);gap:10px}
  .item{border:1px solid var(--line);border-radius:10px;padding:12px;margin-bottom:12px;background:#f8fafd;position:relative}
  .item .item-head{display:flex;align-items:center;gap:8px;margin-bottom:10px}
  .item .item-head .ttl{font-weight:600;font-size:13px;flex:1}
  .icon-btn{display:flex;align-items:center;gap:8px;border:1px solid var(--line);background:#fff;border-radius:9px;padding:8px 10px;cursor:pointer;width:100%}
  .icon-btn .ibx{width:26px;height:26px;display:flex;align-items:center;justify-content:center;color:var(--brand-d)}
  .icon-btn .nm{font-size:13px;color:var(--mut)}
  .imgfield{display:flex;align-items:center;gap:10px}
  .imgfield .thumb{width:52px;height:52px;border-radius:8px;border:1px solid var(--line);object-fit:cover;background:#f4eee8;flex:none}
  .gal-grid{display:flex;gap:8px;flex-wrap:wrap}
  .gal-grid .gi{position:relative;width:60px;height:60px}
  .gal-grid .gi img{width:100%;height:100%;object-fit:cover;border-radius:8px;border:1px solid var(--line)}
  .gal-grid .gi .x{position:absolute;top:-6px;right:-6px;width:20px;height:20px;border-radius:50%;background:var(--err);color:#fff;border:0;cursor:pointer;font-size:12px;line-height:1}
  .mini{font-size:12px;color:var(--mut)}
  .iconpop{position:fixed;z-index:300;background:#fff;border:1px solid var(--line);border-radius:12px;box-shadow:0 14px 40px rgba(0,0,0,.18);
    padding:10px;width:300px;max-height:320px;overflow-y:auto;display:none}
  .iconpop.show{display:block}
  .iconpop .grid{display:grid;grid-template-columns:repeat(6,1fr);gap:4px}
  .iconpop .gi2{aspect-ratio:1;display:flex;align-items:center;justify-content:center;border-radius:8px;cursor:pointer;color:#3a4256}
  .iconpop .gi2:hover{background:var(--soft);color:var(--brand-d)}
  .iconpop .gi2.none{font-size:10px;color:var(--mut)}
  .addbar{display:flex;gap:8px;flex-wrap:wrap;margin-top:6px}
  .savebar{position:fixed;left:0;right:420px;bottom:0;background:#fff;border-top:1px solid var(--line);padding:12px 26px;display:flex;align-items:center;gap:12px;z-index:40}
  @media(max-width:980px){.savebar{right:0}}
  .color-row{display:flex;align-items:center;gap:8px}
  .color-row input[type=color]{width:40px;height:36px;padding:2px;flex:none}
  .reorder{display:flex;flex-direction:column;gap:2px}
  .reorder button{border:1px solid var(--line);background:#fff;border-radius:6px;width:26px;height:18px;cursor:pointer;padding:0;font-size:10px;color:var(--mut)}
  .tag{font-size:11px;background:var(--soft);color:var(--brand-d);padding:2px 8px;border-radius:20px}
  .addmenu{display:none;position:absolute;bottom:calc(100% + 6px);left:0;z-index:60;background:#fff;border:1px solid var(--line);
    border-radius:12px;box-shadow:0 14px 40px rgba(10,20,50,.18);padding:6px;width:260px;max-height:320px;overflow-y:auto}
  .addmenu.show{display:block}
  .addmenu .addopt{padding:9px 12px;border-radius:8px;cursor:pointer;font-size:14px}
  .addmenu .addopt:hover{background:var(--soft);color:var(--brand-d)}
</style>

<div class="editor">
  <!-- ====================== FORM ====================== -->
  <div class="form-pane">
    <a href="/admin" class="mini" style="text-decoration:none">← Voltar para minhas bios</a>
    <h1 style="font-size:22px;margin:8px 0 4px"><?= $isNew ? 'Nova bio' : 'Editar bio' ?></h1>
    <p class="mini" style="margin-bottom:18px">Tudo é opcional — deixe em branco o que não quiser exibir.</p>

    <!-- URL / status -->
    <div class="sec">
      <h3>🔗 Endereço da página <span class="chev">▾</span></h3>
      <div class="sec-body">
        <div class="field">
          <label>URL da empresa</label>
          <div style="display:flex;align-items:center;gap:6px">
            <span class="mini" style="white-space:nowrap">bio.publishdev.com.br/</span>
            <input id="slug" value="<?= e($slug) ?>" placeholder="nome-da-empresa" style="flex:1">
          </div>
          <p class="mini" style="margin-top:5px">Deixe em branco para gerar a partir do nome.</p>
        </div>
        <label style="display:flex;align-items:center;gap:8px;cursor:pointer">
          <input type="checkbox" id="active" <?= $active ? 'checked' : '' ?> style="width:auto">
          <span>Página ativa (visível ao público)</span>
        </label>
      </div>
    </div>

    <!-- Perfil -->
    <div class="sec">
      <h3>👤 Perfil <span class="chev">▾</span></h3>
      <div class="sec-body" id="sec-profile"></div>
    </div>

    <!-- Aparência -->
    <div class="sec collapsed">
      <h3>🎨 Aparência e cores <span class="chev">▾</span></h3>
      <div class="sec-body" id="sec-theme"></div>
    </div>

    <!-- Features -->
    <div class="sec">
      <h3>✨ Ícones de destaque <span class="chev">▾</span></h3>
      <div class="sec-body">
        <p class="mini" style="margin-bottom:10px">A linha de ícones abaixo do nome (ex.: Moda casual, Roupas e Acessórios...).</p>
        <div id="list-features"></div>
        <div class="addbar"><button class="btn ghost sm" onclick="addFeature()">+ Adicionar ícone</button></div>
      </div>
    </div>

    <!-- Blocos -->
    <div class="sec">
      <h3>🧩 Blocos e links <span class="chev">▾</span></h3>
      <div class="sec-body">
        <p class="mini" style="margin-bottom:10px">Adicione quantos blocos quiser, de qualquer tipo. Ordene com ▲▼.</p>
        <div id="list-blocks"></div>
        <div class="addbar" id="addwrap" style="position:relative">
          <button class="btn" type="button" onclick="toggleAddMenu(event)">+ Adicionar bloco ▾</button>
          <div class="addmenu" id="addmenu"></div>
        </div>
      </div>
    </div>

    <!-- Rodapé -->
    <div class="sec">
      <h3>🛡️ Rodapé (selos de confiança) <span class="chev">▾</span></h3>
      <div class="sec-body">
        <div class="field" style="max-width:220px">
          <label>Ícone decorativo do topo</label>
          <div id="footer-icon"></div>
        </div>
        <div id="list-badges"></div>
        <div class="addbar"><button class="btn ghost sm" onclick="addBadge()">+ Adicionar selo</button></div>
      </div>
    </div>

    <!-- SEO -->
    <div class="sec collapsed">
      <h3>🔍 SEO / compartilhamento <span class="chev">▾</span></h3>
      <div class="sec-body" id="sec-seo"></div>
    </div>
  </div>

  <!-- ====================== PREVIEW ====================== -->
  <div class="preview-pane">
    <div class="phone"><iframe id="preview"></iframe></div>
    <a class="plink btn ghost sm" id="open-live" href="#" target="_blank">Abrir página em nova aba ↗</a>
  </div>
</div>

<!-- Barra salvar -->
<div class="savebar">
  <button class="btn" id="btn-save" onclick="save()"><?= icon('check', 18) ?> Salvar bio</button>
  <span class="mini" id="save-hint">As alterações aparecem ao vivo na pré-visualização.</span>
</div>

<!-- Popover de ícones -->
<div class="iconpop" id="iconpop"><div class="grid" id="iconpop-grid"></div></div>
<div class="toast" id="toast"></div>

<!-- inputs de upload reutilizáveis -->
<input type="file" id="uploader" accept="image/*" style="display:none">

<script>
const CSRF   = <?= json_encode($csrf) ?>;
const EDIT_ID = <?= (int) $editId ?>;
const ICONS  = <?= json_encode($iconsJs, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?>;
const ICON_LIST = <?= json_encode(icon_list()) ?>;
let STATE = <?= json_encode($config, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?>;

// Garante estrutura mínima
STATE.theme    = STATE.theme    || {};
STATE.profile  = STATE.profile  || {};
STATE.features = STATE.features || [];
STATE.blocks   = STATE.blocks   || [];
STATE.footer   = STATE.footer   || {badges:[]};
STATE.footer.badges = STATE.footer.badges || [];
STATE.seo      = STATE.seo      || {};

const $ = s => document.querySelector(s);
const el = (t,c,h)=>{const e=document.createElement(t);if(c)e.className=c;if(h!=null)e.innerHTML=h;return e;};
function iconSvg(name){ if(!name) return ''; return ICONS[name]||ICONS['star']||''; }

/* ---------- bind helpers ---------- */
function setPath(obj,path,val){const p=path.split('.');let o=obj;for(let i=0;i<p.length-1;i++){o=o[p[i]]=o[p[i]]||{};}o[p[p.length-1]]=val;}
function getPath(obj,path){return path.split('.').reduce((o,k)=>o&&o[k],obj);}

/* ---------- ícone picker ---------- */
let pickTarget=null; // function(name)
function openIconPicker(anchor,current,cb){
  pickTarget=cb;
  const grid=$('#iconpop-grid'); grid.innerHTML='';
  const none=el('div','gi2 none','sem ícone'); none.onclick=()=>{cb('');closeIconPicker();}; grid.appendChild(none);
  ICON_LIST.forEach(n=>{const g=el('div','gi2',iconSvg(n));g.title=n;if(n===current)g.style.background='#f1e8e0';g.onclick=()=>{cb(n);closeIconPicker();};grid.appendChild(g);});
  const pop=$('#iconpop'); pop.classList.add('show');
  const r=anchor.getBoundingClientRect();
  let top=r.bottom+6, left=r.left;
  if(left+300>window.innerWidth)left=window.innerWidth-310;
  if(top+320>window.innerHeight)top=Math.max(10,r.top-326);
  pop.style.top=top+'px'; pop.style.left=left+'px';
}
function closeIconPicker(){$('#iconpop').classList.remove('show');pickTarget=null;}
document.addEventListener('click',e=>{const p=$('#iconpop');if(p.classList.contains('show')&&!p.contains(e.target)&&!e.target.closest('.icon-btn'))closeIconPicker();});

// Cria um botão de seleção de ícone que escreve em state[path]
function iconControl(current,onChange){
  const btn=el('button','icon-btn');
  const render=(name)=>{btn.innerHTML=`<span class="ibx">${name?iconSvg(name):''}</span><span class="nm">${name||'Escolher ícone'}</span>`;};
  render(current||'');
  btn.type='button';
  btn.onclick=(ev)=>{ev.preventDefault();openIconPicker(btn,current,(name)=>{current=name;render(name);onChange(name);});};
  return btn;
}

/* ---------- upload ---------- */
function pickImage(cb){
  const u=$('#uploader'); u.value='';
  u.onchange=async()=>{
    if(!u.files[0])return;
    const fd=new FormData(); fd.append('file',u.files[0]); fd.append('csrf',CSRF);
    toast('Enviando imagem...');
    try{
      const r=await fetch('/admin/upload',{method:'POST',body:fd});
      const j=await r.json();
      if(j.url){cb(j.url);toast('Imagem enviada','ok');}
      else toast(j.error||'Falha no upload','err');
    }catch(e){toast('Erro de rede no upload','err');}
  };
  u.click();
}
// Campo de imagem única
function imageControl(current,onChange){
  const wrap=el('div','imgfield');
  const img=el('img','thumb'); img.src=current||''; img.style.display=current?'block':'none';
  const btn=el('button','btn ghost sm',current?'Trocar':'Enviar imagem'); btn.type='button';
  const rm=el('button','btn danger sm','Remover'); rm.type='button'; rm.style.display=current?'inline-flex':'none';
  btn.onclick=()=>pickImage(u=>{img.src=u;img.style.display='block';rm.style.display='inline-flex';btn.textContent='Trocar';onChange(u);});
  rm.onclick=()=>{img.style.display='none';rm.style.display='none';btn.textContent='Enviar imagem';onChange('');};
  wrap.append(img,btn,rm); return wrap;
}

/* ---------- campos genéricos ---------- */
function textField(label,val,ph,onChange,multiline){
  const f=el('div','field');
  f.appendChild(el('label',null,label));
  const inp=multiline?el('textarea'):el('input');
  if(multiline)inp.rows=2;
  inp.value=val||''; inp.placeholder=ph||'';
  inp.oninput=()=>{onChange(inp.value);schedulePreview();};
  f.appendChild(inp); return f;
}

/* ====================================================================
   PERFIL
==================================================================== */
function renderProfile(){
  const c=$('#sec-profile'); c.innerHTML='';
  const p=STATE.profile;
  // imagem
  const imgF=el('div','field'); imgF.appendChild(el('label',null,'Foto / logo do perfil'));
  imgF.appendChild(imageControl(p.image,v=>{p.image=v;schedulePreview();}));
  c.appendChild(imgF);
  // estilo da imagem
  const styF=el('div','field'); styF.appendChild(el('label',null,'Formato da foto'));
  const sel=el('select'); ['circle','rounded','square'].forEach(o=>{const op=el('option',null,{circle:'Círculo',rounded:'Arredondado',square:'Quadrado'}[o]);op.value=o;if((p.imageStyle||'circle')===o)op.selected=true;sel.appendChild(op);});
  sel.onchange=()=>{p.imageStyle=sel.value;schedulePreview();};
  styF.appendChild(sel); c.appendChild(styF);
  // nome + ícone
  const r=el('div','row2');
  r.appendChild(textField('Nome da empresa',p.name,'Clara Bella',v=>p.name=v));
  const icF=el('div','field'); icF.appendChild(el('label',null,'Ícone ao lado do nome'));
  icF.appendChild(iconControl(p.nameIcon,v=>{p.nameIcon=v;schedulePreview();}));
  r.appendChild(icF); c.appendChild(r);
  // subtitle
  c.appendChild(textField('Texto abaixo do nome',p.subtitle,'LOJA FÍSICA E VIRTUAL',v=>p.subtitle=v));
  // bio
  c.appendChild(textField('Descrição (opcional)',p.bio,'Uma frase sobre a sua marca...',v=>p.bio=v,true));
}

/* ====================================================================
   TEMA
==================================================================== */
function colorField(label,key){
  const def={bg:'#f4ebe4',bg2:'#efe2d8',primary:'#b98e6f',accent:'#c9a08a',text:'#4a3b33',muted:'#9b8b80',cardBg:'#e7d6ca',cardBg2:'#f0e4db'}[key];
  const f=el('div','field'); f.appendChild(el('label',null,label));
  const row=el('div','color-row');
  const cur=STATE.theme[key]||def;
  const cp=el('input'); cp.type='color'; cp.value=cur;
  const tx=el('input'); tx.value=cur; tx.style.flex='1';
  cp.oninput=()=>{STATE.theme[key]=cp.value;tx.value=cp.value;schedulePreview();};
  tx.oninput=()=>{STATE.theme[key]=tx.value;if(/^#[0-9a-f]{6}$/i.test(tx.value))cp.value=tx.value;schedulePreview();};
  row.append(cp,tx); f.appendChild(row); return f;
}
function renderTheme(){
  const c=$('#sec-theme'); c.innerHTML='';
  const fF=el('div','field'); fF.appendChild(el('label',null,'Fonte dos títulos'));
  const sel=el('select'); [['serif','Elegante (serifada)'],['sans','Moderna (sem serifa)']].forEach(([v,t])=>{const o=el('option',null,t);o.value=v;if((STATE.theme.font||'serif')===v)o.selected=true;sel.appendChild(o);});
  sel.onchange=()=>{STATE.theme.font=sel.value;schedulePreview();}; fF.appendChild(sel); c.appendChild(fF);
  const presets=el('div','field');
  presets.appendChild(el('label',null,'Paletas prontas'));
  const pr=el('div','addbar');
  const palettes={
    'Nude (referência)':{bg:'#f4ebe4',bg2:'#efe2d8',primary:'#b98e6f',accent:'#c9a08a',text:'#4a3b33',muted:'#9b8b80',cardBg:'#e7d6ca',cardBg2:'#f0e4db'},
    'Rosé':{bg:'#fdf0f1',bg2:'#f8dfe3',primary:'#c98a96',accent:'#d9a3ad',text:'#4a3338',muted:'#a78a8f',cardBg:'#f4d9de',cardBg2:'#fbe8ea'},
    'Verde sálvia':{bg:'#eef2ea',bg2:'#e1e9da',primary:'#7d9871',accent:'#9bb08f',text:'#36402f',muted:'#828d77',cardBg:'#dde6d4',cardBg2:'#edf1e8'},
    'Escuro':{bg:'#23201e',bg2:'#1a1816',primary:'#c9a07f',accent:'#d8b596',text:'#f3ece5',muted:'#a89a8d',cardBg:'#322d29',cardBg2:'#2b2622'},
    'Azul sereno':{bg:'#eef3f7',bg2:'#dde8f0',primary:'#5d87a8',accent:'#7ba0bd',text:'#283848',muted:'#7d8d9c',cardBg:'#d6e3ed',cardBg2:'#e8f0f6'},
  };
  Object.entries(palettes).forEach(([nm,pal])=>{const b=el('button','btn ghost sm',nm);b.type='button';b.onclick=()=>{Object.assign(STATE.theme,pal);renderTheme();schedulePreview();};pr.appendChild(b);});
  presets.appendChild(pr); c.appendChild(presets);
  const grid=el('div','row2');
  grid.appendChild(colorField('Fundo (topo)','bg'));
  grid.appendChild(colorField('Fundo (base)','bg2'));
  grid.appendChild(colorField('Cor principal','primary'));
  grid.appendChild(colorField('Cor de destaque','accent'));
  grid.appendChild(colorField('Texto','text'));
  grid.appendChild(colorField('Texto suave','muted'));
  grid.appendChild(colorField('Cards (interno)','cardBg'));
  grid.appendChild(colorField('Cards (fundo)','cardBg2'));
  c.appendChild(grid);
}

/* ====================================================================
   FEATURES
==================================================================== */
function renderFeatures(){
  const c=$('#list-features'); c.innerHTML='';
  STATE.features.forEach((f,i)=>{
    const it=el('div','item');
    const head=el('div','item-head');
    head.appendChild(el('span','tag','Ícone '+(i+1)));
    head.appendChild(reorderCtl(STATE.features,i,renderFeatures));
    const del=el('button','btn danger sm','✕'); del.type='button'; del.style.marginLeft='auto'; del.onclick=()=>{STATE.features.splice(i,1);renderFeatures();schedulePreview();};
    head.appendChild(del); it.appendChild(head);
    const icF=el('div','field'); icF.appendChild(el('label',null,'Ícone'));
    icF.appendChild(iconControl(f.icon,v=>{f.icon=v;schedulePreview();})); it.appendChild(icF);
    const r=el('div','row2');
    r.appendChild(textField('Linha 1',f.line1,'Moda casual',v=>f.line1=v));
    r.appendChild(textField('Linha 2',f.line2,'e executiva',v=>f.line2=v));
    it.appendChild(r); c.appendChild(it);
  });
}
function addFeature(){STATE.features.push({icon:'star',line1:'',line2:''});renderFeatures();schedulePreview();}

/* ====================================================================
   BLOCOS
==================================================================== */
const BLOCK_DEFS={
  whatsapp:{name:'WhatsApp',fields:['icon','label','title','phone','message','bg']},
  product:{name:'Produto',fields:['title','priceLabel','price','image','url','bg']},
  instagram:{name:'Instagram',fields:['label','title','gallery','url','bg']},
  link:{name:'Link / Card',fields:['icon','label','title','sub','url','bg']},
  cta:{name:'Banner CTA',fields:['icon','title','button','buttonIcon','url','bg']},
  text:{name:'Texto livre',fields:['title']},
  heading:{name:'Título de seção',fields:['title','sub','align']},
  image:{name:'Imagem',fields:['image','caption','url']},
  gallery:{name:'Galeria de imagens',fields:['gallery']},
  video:{name:'Vídeo (YouTube)',fields:['title','video']},
  divider:{name:'Divisória / espaço',fields:['icon']},
  socials:{name:'Redes sociais',fields:['socitems']},
  buttons:{name:'Grupo de botões',fields:['btnitems','inline']},
  pix:{name:'Chave PIX',fields:['label','pixkey','pixname']},
  map:{name:'Mapa / Endereço',fields:['title','address']},
  embed:{name:'HTML / Incorporar',fields:['embed']},
};
// Rótulos amigáveis dos tipos no menu "adicionar"
const BLOCK_MENU=[
  ['whatsapp','💬 WhatsApp'],['link','🔗 Link / Card'],['buttons','🔘 Grupo de botões'],
  ['product','🛍️ Produto'],['cta','✨ Banner CTA'],['socials','📱 Redes sociais'],
  ['instagram','📸 Instagram'],['image','🖼️ Imagem'],['gallery','🎞️ Galeria'],
  ['video','▶️ Vídeo (YouTube)'],['map','📍 Mapa / Endereço'],['pix','💠 Chave PIX'],
  ['heading','🔤 Título de seção'],['text','📝 Texto livre'],['divider','➖ Divisória / espaço'],
  ['embed','</> HTML / Incorporar'],
];
const FIELD_META={
  label:['Rótulo pequeno (maiúsculas)','FALE CONOSCO'],
  title:['Título principal','Peça agora no WhatsApp'],
  sub:['Texto secundário','Detalhes...'],
  phone:['Telefone WhatsApp (com DDD)','5582999999999'],
  message:['Mensagem automática','Olá! Vim pela bio...'],
  url:['Link de destino (URL)','https://...'],
  priceLabel:['Texto antes do preço','Por apenas'],
  price:['Preço','R$ 34,99'],
  button:['Texto do botão','ESCOLHA AGORA'],
  video:['Link do vídeo (YouTube)','https://youtu.be/...'],
  caption:['Legenda (opcional)','Texto abaixo da imagem'],
  pixkey:['Chave PIX','email, CPF, telefone ou chave aleatória'],
  pixname:['Nome do recebedor (opcional)','Loja Clara Bella'],
  address:['Endereço para o mapa','Av. Exemplo, 123 - Maceió/AL'],
  embed:['Código HTML / incorporar (avançado)','<iframe ...></iframe> ou qualquer HTML'],
};
function renderBlocks(){
  const c=$('#list-blocks'); c.innerHTML='';
  STATE.blocks.forEach((b,i)=>{
    const def=BLOCK_DEFS[b.type]||BLOCK_DEFS.link;
    const it=el('div','item');
    const head=el('div','item-head');
    head.appendChild(el('span','tag',def.name));
    head.appendChild(reorderCtl(STATE.blocks,i,renderBlocks));
    const del=el('button','btn danger sm','✕'); del.type='button'; del.style.marginLeft='auto'; del.onclick=()=>{STATE.blocks.splice(i,1);renderBlocks();schedulePreview();};
    head.appendChild(del); it.appendChild(head);
    def.fields.forEach(fl=>{
      if(fl==='icon'||fl==='buttonIcon'){
        const f=el('div','field'); f.appendChild(el('label',null,fl==='buttonIcon'?'Ícone do botão':(b.type==='divider'?'Ícone central (opcional)':'Ícone')));
        f.appendChild(iconControl(b[fl],v=>{b[fl]=v;schedulePreview();})); it.appendChild(f);
      }else if(fl==='image'){
        const f=el('div','field'); f.appendChild(el('label',null,'Imagem'));
        f.appendChild(imageControl(b.image,v=>{b.image=v;schedulePreview();})); it.appendChild(f);
      }else if(fl==='gallery'){
        it.appendChild(galleryControl(b, b.type==='gallery'?12:4));
      }else if(fl==='align'){
        const f=el('div','field'); f.appendChild(el('label',null,'Alinhamento'));
        const sel=el('select'); [['center','Centro'],['left','Esquerda'],['right','Direita']].forEach(([v,t])=>{const o=el('option',null,t);o.value=v;if((b.align||'center')===v)o.selected=true;sel.appendChild(o);});
        sel.onchange=()=>{b.align=sel.value;schedulePreview();}; f.appendChild(sel); it.appendChild(f);
      }else if(fl==='inline'){
        const f=el('div','field');
        const lab=el('label',null,''); lab.style.display='flex'; lab.style.alignItems='center'; lab.style.gap='8px'; lab.style.cursor='pointer';
        const ck=el('input'); ck.type='checkbox'; ck.style.width='auto'; ck.checked=!!b.inline; ck.onchange=()=>{b.inline=ck.checked;schedulePreview();};
        lab.append(ck,document.createTextNode('Botões lado a lado')); f.appendChild(lab); it.appendChild(f);
      }else if(fl==='bg'){
        it.appendChild(bgControl(b));
      }else if(fl==='socitems'){
        it.appendChild(itemsRepeater(b,'socials'));
      }else if(fl==='btnitems'){
        it.appendChild(itemsRepeater(b,'buttons'));
      }else if(fl==='embed'){
        const f=el('div','field'); const [lb,ph]=FIELD_META.embed;
        f.appendChild(el('label',null,lb));
        const ta=el('textarea'); ta.rows=5; ta.value=b.embed||''; ta.placeholder=ph; ta.style.fontFamily='ui-monospace,monospace'; ta.style.fontSize='12px';
        ta.oninput=()=>{b.embed=ta.value;schedulePreview();}; f.appendChild(ta);
        f.appendChild(el('p','mini','⚠️ Cole aqui qualquer código HTML/incorporar (mapas, players, formulários...). Use apenas conteúdo confiável.'));
        it.appendChild(f);
      }else{
        const [lb,ph]=FIELD_META[fl]||[fl,''];
        const ml=(fl==='message'||(fl==='title'&&(b.type==='cta'||b.type==='text')));
        it.appendChild(textField(lb,b[fl],ph,v=>b[fl]=v,ml));
      }
    });
    c.appendChild(it);
  });
}
function galleryControl(b,max){
  max=max||4; b.gallery=b.gallery||[];
  const f=el('div','field'); f.appendChild(el('label',null,'Galeria de imagens (até '+max+')'));
  const grid=el('div','gal-grid');
  const redraw=()=>{
    grid.innerHTML='';
    b.gallery.forEach((g,gi)=>{const c=el('div','gi');const im=el('img');im.src=g;const x=el('button','x','✕');x.type='button';x.onclick=()=>{b.gallery.splice(gi,1);redraw();schedulePreview();};c.append(im,x);grid.appendChild(c);});
    if(b.gallery.length<max){const add=el('button','btn ghost sm','+ Imagem');add.type='button';add.style.height='60px';add.onclick=()=>pickImage(u=>{b.gallery.push(u);redraw();schedulePreview();});grid.appendChild(add);}
  };
  redraw(); f.appendChild(grid); return f;
}
// Controle de imagem de fundo do botão (textura atrás do texto)
function bgControl(o){
  const wrap=el('div','field'); wrap.style.borderTop='1px dashed var(--line)'; wrap.style.paddingTop='12px'; wrap.style.marginTop='4px';
  wrap.appendChild(el('label',null,'🖼️ Imagem de fundo do botão (opcional)'));
  wrap.appendChild(el('p','mini','Textura/efeito atrás do texto, como um padrão de bolinhas.'));
  wrap.appendChild(imageControl(o.bgImage,v=>{o.bgImage=v;schedulePreview();}));
  const row=el('div','row2'); row.style.marginTop='8px';
  // repetir
  const rf=el('div','field');
  const rl=el('label',null,''); rl.style.display='flex';rl.style.gap='8px';rl.style.alignItems='center';rl.style.cursor='pointer';
  const ck=el('input');ck.type='checkbox';ck.style.width='auto';ck.checked=!!o.bgRepeat;ck.onchange=()=>{o.bgRepeat=ck.checked;schedulePreview();};
  rl.append(ck,document.createTextNode('Repetir (padrão/textura)')); rf.appendChild(rl); row.appendChild(rf);
  // camada de leitura
  const of=el('div','field'); of.appendChild(el('label',null,'Camada sobre a imagem'));
  const sel=el('select');[['nenhum','Sem camada (imagem cheia)'],['leve','Leve'],['medio','Média'],['forte','Forte (texto bem legível)']].forEach(([v,t])=>{const op=el('option',null,t);op.value=v;if((o.bgOverlay||'medio')===v)op.selected=true;sel.appendChild(op);});
  sel.onchange=()=>{o.bgOverlay=sel.value;schedulePreview();}; of.appendChild(sel); row.appendChild(of);
  wrap.appendChild(row); return wrap;
}
// Repetidor de itens com link (redes sociais / grupo de botões)
function itemsRepeater(b,kind){
  b.items=b.items||[];
  const f=el('div','field');
  f.appendChild(el('label',null,kind==='socials'?'Ícones com link':'Botões'));
  const list=el('div');
  const redraw=()=>{
    list.innerHTML='';
    b.items.forEach((it,ii)=>{
      const row=el('div','item'); row.style.background='#fff';
      const head=el('div','item-head');
      head.appendChild(el('span','tag',(kind==='socials'?'Rede ':'Botão ')+(ii+1)));
      head.appendChild(reorderCtl(b.items,ii,redraw));
      const del=el('button','btn danger sm','✕'); del.type='button'; del.style.marginLeft='auto';
      del.onclick=()=>{b.items.splice(ii,1);redraw();schedulePreview();};
      head.appendChild(del); row.appendChild(head);
      const icF=el('div','field'); icF.appendChild(el('label',null,'Ícone'));
      icF.appendChild(iconControl(it.icon,v=>{it.icon=v;schedulePreview();})); row.appendChild(icF);
      if(kind==='buttons') row.appendChild(textField('Texto do botão',it.label,'Ex.: Cardápio',v=>it.label=v));
      else row.appendChild(textField('Nome (dica ao passar o mouse)',it.label,'Ex.: Instagram',v=>it.label=v));
      row.appendChild(textField('Link (URL)',it.url,'https://...',v=>it.url=v));
      if(kind==='buttons') row.appendChild(bgControl(it));
      list.appendChild(row);
    });
  };
  redraw(); f.appendChild(list);
  const add=el('button','btn ghost sm',kind==='socials'?'+ Rede social':'+ Botão'); add.type='button';
  add.onclick=()=>{b.items.push(kind==='socials'?{icon:'instagram-color',label:'',url:''}:{icon:'',label:'',url:''});redraw();schedulePreview();};
  f.appendChild(add); return f;
}
function addBlock(type){
  const tpl={
    whatsapp:{type:'whatsapp',icon:'whatsapp',label:'FALE CONOSCO',title:'Peça agora no WhatsApp',phone:'',message:'Olá! Vim pela sua bio.'},
    product:{type:'product',title:'',priceLabel:'Por apenas',price:'',image:'',url:''},
    instagram:{type:'instagram',label:'SIGA NOSSO INSTAGRAM',title:'Conteúdos e novidades todos os dias',gallery:[],url:''},
    link:{type:'link',icon:'globe',label:'',title:'',sub:'',url:''},
    cta:{type:'cta',icon:'sparkles',title:'ESCOLHA AGORA',button:'CLIQUE AQUI',buttonIcon:'heart',url:''},
    text:{type:'text',title:''},
    heading:{type:'heading',title:'',sub:'',align:'center'},
    image:{type:'image',image:'',caption:'',url:''},
    gallery:{type:'gallery',gallery:[]},
    video:{type:'video',title:'',video:''},
    divider:{type:'divider',icon:'heart'},
    socials:{type:'socials',items:[{icon:'instagram-color',label:'',url:''}]},
    buttons:{type:'buttons',inline:false,items:[{icon:'',label:'',url:''}]},
    pix:{type:'pix',label:'PAGUE COM PIX',pixkey:'',pixname:''},
    map:{type:'map',title:'',address:''},
    embed:{type:'embed',embed:''},
  }[type];
  STATE.blocks.push(JSON.parse(JSON.stringify(tpl)));
  renderBlocks(); schedulePreview();
  // rola até o novo bloco
  setTimeout(()=>{const items=$('#list-blocks').children;if(items.length)items[items.length-1].scrollIntoView({behavior:'smooth',block:'center'});},60);
}
// Menu suspenso "adicionar bloco"
function toggleAddMenu(ev){
  ev.stopPropagation();
  const m=$('#addmenu'); m.classList.toggle('show');
}
function buildAddMenu(){
  const m=$('#addmenu'); m.innerHTML='';
  BLOCK_MENU.forEach(([t,lbl])=>{const a=el('div','addopt',lbl);a.onclick=()=>{addBlock(t);$('#addmenu').classList.remove('show');};m.appendChild(a);});
}
document.addEventListener('click',e=>{if(!e.target.closest('#addwrap'))$('#addmenu').classList.remove('show');});

/* ====================================================================
   RODAPÉ
==================================================================== */
function renderFooter(){
  const fi=$('#footer-icon'); fi.innerHTML='';
  fi.appendChild(iconControl(STATE.footer.icon||'heart',v=>{STATE.footer.icon=v;schedulePreview();}));
  const c=$('#list-badges'); c.innerHTML='';
  STATE.footer.badges.forEach((bd,i)=>{
    const it=el('div','item');
    const head=el('div','item-head'); head.appendChild(el('span','tag','Selo '+(i+1)));
    head.appendChild(reorderCtl(STATE.footer.badges,i,renderFooter));
    const del=el('button','btn danger sm','✕'); del.type='button'; del.style.marginLeft='auto'; del.onclick=()=>{STATE.footer.badges.splice(i,1);renderFooter();schedulePreview();};
    head.appendChild(del); it.appendChild(head);
    const r=el('div','row2');
    const icF=el('div','field'); icF.appendChild(el('label',null,'Ícone')); icF.appendChild(iconControl(bd.icon,v=>{bd.icon=v;schedulePreview();}));
    r.appendChild(icF);
    r.appendChild(textField('Texto',bd.text,'Compra segura',v=>bd.text=v));
    it.appendChild(r); c.appendChild(it);
  });
}
function addBadge(){STATE.footer.badges.push({icon:'check',text:''});renderFooter();schedulePreview();}

/* ====================================================================
   SEO
==================================================================== */
function renderSeo(){
  const c=$('#sec-seo'); c.innerHTML='';
  STATE.seo=STATE.seo||{};
  c.appendChild(textField('Título da aba / compartilhamento',STATE.seo.title,'Clara Bella',v=>STATE.seo.title=v));
  c.appendChild(textField('Descrição',STATE.seo.description,'Loja física e virtual de moda...',v=>STATE.seo.description=v,true));
}

/* ---------- reorder ---------- */
function reorderCtl(arr,i,rerender){
  const w=el('div','reorder');
  const up=el('button',null,'▲'); up.type='button'; up.onclick=()=>{if(i>0){[arr[i-1],arr[i]]=[arr[i],arr[i-1]];rerender();schedulePreview();}};
  const dn=el('button',null,'▼'); dn.type='button'; dn.onclick=()=>{if(i<arr.length-1){[arr[i+1],arr[i]]=[arr[i],arr[i+1]];rerender();schedulePreview();}};
  w.append(up,dn); return w;
}

/* ---------- preview ---------- */
let prevTimer=null;
function schedulePreview(){clearTimeout(prevTimer);prevTimer=setTimeout(refreshPreview,350);}
async function refreshPreview(){
  try{
    const fd=new FormData(); fd.append('payload',JSON.stringify(STATE));
    const r=await fetch('/admin/preview',{method:'POST',body:fd});
    const html=await r.text();
    $('#preview').srcdoc=html;
  }catch(e){}
}

/* ---------- salvar ---------- */
async function save(){
  const btn=$('#btn-save'); btn.disabled=true;
  const fd=new FormData();
  fd.append('csrf',CSRF);
  fd.append('id',EDIT_ID);
  fd.append('slug',$('#slug').value);
  fd.append('active',$('#active').checked?1:0);
  fd.append('payload',JSON.stringify(STATE));
  try{
    const r=await fetch('/admin/save',{method:'POST',body:fd});
    const j=await r.json();
    if(j.ok){
      toast('Bio salva com sucesso!','ok');
      $('#slug').value=j.slug;
      $('#open-live').href=j.url;
      if(EDIT_ID===0){setTimeout(()=>location.href='/admin/edit/'+j.id,700);}
    }else toast(j.error||'Erro ao salvar','err');
  }catch(e){toast('Erro de rede ao salvar','err');}
  btn.disabled=false;
}

/* ---------- toast ---------- */
let toastTimer=null;
function toast(msg,kind){const t=$('#toast');t.textContent=msg;t.className='toast show '+(kind||'');clearTimeout(toastTimer);toastTimer=setTimeout(()=>t.className='toast',2600);}

/* ---------- seções colapsáveis ---------- */
document.querySelectorAll('.sec>h3').forEach(h=>h.onclick=()=>h.parentElement.classList.toggle('collapsed'));

/* ---------- slug auto ---------- */
$('#slug').addEventListener('blur',()=>{ if(!$('#slug').value && STATE.profile.name){ $('#slug').value=slugify(STATE.profile.name);} });
function slugify(t){return t.toLowerCase().normalize('NFD').replace(/[̀-ͯ]/g,'').replace(/[^a-z0-9]+/g,'-').replace(/^-|-$/g,'');}

/* ---------- init ---------- */
renderProfile(); renderTheme(); renderFeatures(); renderBlocks(); renderFooter(); renderSeo(); buildAddMenu();
$('#open-live').href = '<?= e($slug ? '/' . $slug : '#') ?>';
refreshPreview();
</script>
</body>
</html>

(function(root){
  'use strict';
  const key='ease-publication-history-v1';
  function isMultiple(item){return item.kind==='multiple'||item.multiple===true||Number(item.count)!==1;}
  function read(storage){const raw=storage.getItem(key);if(!raw)return [];const data=JSON.parse(raw);if(!Array.isArray(data))throw new Error('履歴データの形式を確認してください。');return data;}
  function snapshot(item,now=new Date()){
    const fields=['date','prefecture','count','preferences','other','items','smoking','exam','publicNote','ng'];
    const content={};for(const field of fields)content[field]=String(item[field]??'');
    content.certificationRequired=Boolean(item.certificationRequired);
    if(Number.isFinite(item.ruleAgeMax))content.ruleAgeMax=item.ruleAgeMax;
    content.ruleNoSmoking=Boolean(item.ruleNoSmoking);
    content.profile={};for(const field of ['height','age','build'])content.profile[field]=String(item.profile?.[field]??'');
    const match=content.date.match(/^(\d{1,2})月(\d{1,2})日/);
    const eventDate=item.eventDate||(match?now.getFullYear()+'-'+match[1].padStart(2,'0')+'-'+match[2].padStart(2,'0'):'');
    return {publicationId:String(item.publicationId??item.id??''),kind:'single',eventDate,savedAt:now.toISOString(),content};
  }
  function append(storage,item,now=new Date()){
    if(isMultiple(item))return {saved:false,excluded:true};
    const entries=read(storage),entry=snapshot(item,now);
    // Preserve revisions, while repeated saves of the same publication are idempotent.
    if(entries.some(old=>old.publicationId===entry.publicationId&&JSON.stringify(old.content)===JSON.stringify(entry.content)))return {saved:true,duplicate:true};
    storage.setItem(key,JSON.stringify([...entries,entry]));
    return {saved:true};
  }
  function renderContent(container,content){
    const labels={date:'日時',prefecture:'開催地',count:'募集人数',preferences:'希望',other:'その他の希望',items:'希望アイテム',smoking:'喫煙',exam:'依頼者の受検経験',publicNote:'掲載事項',ng:'NG事項'};
    const dl=root.document.createElement('dl');
    for(const [field,label] of Object.entries(labels))if(content[field]||field==='ng'||field==='publicNote'){const dt=root.document.createElement('dt'),dd=root.document.createElement('dd');dt.textContent=field==='publicNote'?'女性からのメッセージ':label;dd.textContent=content[field]||(field==='ng'?'追加の指定なし':'記載なし');dd.style.whiteSpace='pre-wrap';dd.style.overflowWrap='anywhere';dl.append(dt,dd);}
    if(content.ruleAgeMax){const dt=root.document.createElement('dt'),dd=root.document.createElement('dd');dt.textContent='希望年齢';dd.textContent='20〜'+content.ruleAgeMax+'歳';dl.append(dt,dd);}
    if(content.certificationRequired){const p=root.document.createElement('p');p.textContent='応募条件：受検経験者限定';container.append(p);}
    container.append(dl);
    if(content.profile){const p=root.document.createElement('p');p.textContent='女性情報：'+[content.profile.age,content.profile.height,content.profile.build].filter(Boolean).join(' / ');container.append(p);}
  }
  root.EaseArchive={key,isMultiple,read,snapshot,append,renderContent};
})(typeof window==='undefined'?globalThis:window);

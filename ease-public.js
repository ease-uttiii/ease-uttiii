(() => {
  const $=id=>document.getElementById(id);
  const now=new Intl.DateTimeFormat('sv-SE',{timeZone:'Asia/Tokyo',year:'numeric',month:'2-digit',day:'2-digit'}).format(new Date());
  $('today-date').textContent=now.replaceAll('-',' / ');
  let events=[],index=0,timer=null,paused=matchMedia('(prefers-reduced-motion: reduce)').matches;
  const button=$('today-pause');
  function render(){
    const target=$('today-event');target.replaceChildren();
    const e=events[index];
    const h=document.createElement('h3');h.textContent=e?e.prefecture+' / '+e.place:'本日の掲載情報はありません';
    const p=document.createElement('p');p.textContent=e?e.time+'集合 · '+(e.kind==='multiple'?'複数案件（当日のみ表示）':'1対1'):'最新の案内は募集ページをご確認ください。';
    target.append(h,p);
    if(e){const a=document.createElement('a');a.href=e.source;a.target='_blank';a.rel='noopener';a.textContent='公式の掲載内容を確認';target.append(a);}
    $('today-count').textContent=events.length?(index+1)+' / '+events.length:'0件';
    $('today-prev').disabled=$('today-next').disabled=events.length<2;
    button.disabled=events.length<2;button.textContent=paused?'自動切替を再開':'自動切替を停止';
  }
  function schedule(){clearInterval(timer);if(!paused&&events.length>1&&!document.hidden)timer=setInterval(()=>{index=(index+1)%events.length;render();},6500);}
  $('today-prev').onclick=()=>{if(events.length)index=(index+events.length-1)%events.length;render();schedule();};
  $('today-next').onclick=()=>{if(events.length)index=(index+1)%events.length;render();schedule();};
  button.onclick=()=>{paused=!paused;render();schedule();};
  document.addEventListener('visibilitychange',schedule);
  fetch('ease-public-events.json').then(r=>{if(!r.ok)throw new Error('load');return r.json();}).then(data=>{
    events=data.events.filter(e=>e.date===now&&e.status==='scheduled').sort((a,b)=>a.time.localeCompare(b.time));
    const past=data.events.filter(e=>e.date<now&&e.kind!=='multiple'&&['completed','past_listing'].includes(e.status)).sort((a,b)=>b.date.localeCompare(a.date));
    $('public-history').replaceChildren(...past.map(e=>{const article=document.createElement('article');const date=document.createElement('time');date.dateTime=e.date;date.textContent=e.date;const p=document.createElement('p');p.textContent=e.prefecture+' / '+e.place;const detail=document.createElement('small');detail.textContent=e.time+'集合 · '+(e.status==='completed'?'開催完了':'過去掲載例（開催実績未確認）');article.append(date,p,detail);return article;}));
    if(!past.length)$('public-history').textContent='履歴はまだありません。';render();schedule();
  }).catch(()=>{$('today-event').textContent='掲載情報を取得できませんでした。募集ページをご確認ください。';$('public-history').textContent='履歴情報を取得できませんでした。';});
})();

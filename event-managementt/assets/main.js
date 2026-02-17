document.getElementById('year')?.textContent = new Date().getFullYear();

// Tiny interactive tilt effect
const card = document.querySelector('.card-3d');
if(card){
  const box = card.querySelector('.card-front');
  card.addEventListener('mousemove', (e)=>{
    const rect = card.getBoundingClientRect();
    const x = (e.clientX - rect.left) / rect.width - 0.5;
    const y = (e.clientY - rect.top) / rect.height - 0.5;
    box.style.transform = `rotateY(${x*12}deg) rotateX(${y*-8}deg)`;
  });
  card.addEventListener('mouseleave', ()=>{
    box.style.transform='rotateY(0deg) rotateX(0deg)';
  });
}

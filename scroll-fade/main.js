const fadeTargets = document.querySelectorAll(".js-fade");

const observer = new IntersectionObserver(
  (entries) => {
    entries.forEach((entry) => {
      if (entry.isIntersecting) {
        entry.target.classList.add("is-show");
        observer.unobserve(entry.target); // 1回出たら見張りをやめる
      }
    });
  },
  { threshold: 0.15 } // 15%見えたら発動
);

fadeTargets.forEach((target) => observer.observe(target));
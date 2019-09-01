import Plyr from 'plyr';
import 'plyr/dist/plyr.css'

const players = Array.from(document.querySelectorAll('.js-player')).map(p => new Plyr(p,{
    settings: ['loop'],
    controls: [],
    autoplay: true,
    loop: {
        activate: true
        },
    volume: 0,
    clickToPlay: false,
    storage: { enabled: false, fallback: false, iosNative: false }
    })
);


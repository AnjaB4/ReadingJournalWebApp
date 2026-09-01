import * as THREE from '/assets/libs/three/three.module.min.js';
import { OrbitControls } from '/assets/libs/three/examples/jsm/controls/OrbitControls.js';
import { RoomEnvironment } from '/assets/libs/three/examples/jsm/environments/RoomEnvironment.js';



const container = document.getElementById('bookshelf-container');

const contextMenu = document.getElementById('book-context-menu');

const examineButton = document.getElementById('examine-book-btn');

const examineMenuOptions = document.getElementById('examine-menu-options');
const previousPageButton = document.getElementById('previous-page-btn');
const nextPageButton = document.getElementById('next-page-btn');
const exitExamineButton = document.getElementById('exit-examine-btn');

const width = container.clientWidth;
const height = container.clientHeight || 600;


// SCENE
const scene = new THREE.Scene();
scene.background = new THREE.Color(0xffffff);
//#0f0b0b

// CAMERA
const camera = new THREE.OrthographicCamera(
    width / -200, width / 200,
    height / 200, height / -200,
    0.1, 1000
);
camera.position.z = 10;
//camera.position.set(2, 1, 10);
//camera.lookAt(0,0,0);


// LIGHT  ---- za kasnije
const ambientLight = new THREE.AmbientLight(0xffffff, 0.1); //0.6
scene.add(ambientLight);

const directionalLight = new THREE.DirectionalLight(0xffffff, 0.6);//3
directionalLight.position.set(5, 3, 4); // staro 3,5,2  onda 2 5 6
scene.add(directionalLight);

/*const coverLight = new THREE.PointLight(0xffffff, 0, 20);
coverLight.position.set(5, 9, 10);
scene.add(coverLight);*/

const fillLight = new THREE.DirectionalLight(0xffffff, 0.2);//0.4
fillLight.position.set(-5, 2, -2);//-5 2 -2
scene.add(fillLight);


// RENDERER
const renderer = new THREE.WebGLRenderer({ antialias: true });
renderer.setSize(width, height);

renderer.setPixelRatio(Math.min(window.devicePixelRatio, 2));
container.appendChild(renderer.domElement);

    // ENVIRONMENT
    const pmremGenerator = new THREE.PMREMGenerator(renderer);
    /* =Prefiltered, Mipmapped Environment Map; generates it from a cubeMap env. texture, for realistic object lighting
        https://threejs.org/docs/#PMREMGenerator */
    const environmentTexture = pmremGenerator.fromScene(new RoomEnvironment()).texture; 
        // napravi novu sobu i pretvori njeno svetlo u teksturu

    scene.environment = environmentTexture;
    scene.environmentIntensity = 0.5; //0.6

    pmremGenerator.dispose();

renderer.domElement.style.display = 'inline-block';

renderer.domElement.style.cursor = 'grab';///////////////////


// CONTROLS
const controls = new OrbitControls(camera, renderer.domElement);
controls.enableZoom = true;
//controls.enablePan = false;
controls.maxPolarAngle = Math.PI/2;
controls.minPolarAngle = 0;

controls.enableRotate = true;
//controls.enableZoom = false;
controls.enablePan = true;


// SCROLL
controls.mouseButtons = {
    LEFT: THREE.MOUSE.PAN,
    MIDDLE: THREE.MOUSE.DOLLY,
    RIGHT: THREE.MOUSE.ROTATE
};

controls.touches = {
    ONE: THREE.TOUCH.PAN,
    TWO: THREE.TOUCH.DOLLY_PAN
};


// RAYCASTER
const raycaster = new THREE.Raycaster();
const mouse = new THREE.Vector2();

let contextMenuBook = null; //cuvaj knjigu za koju treba prikazati context menu
let currentlyExaminedBook = null; //koja knjiga se trenutno istrazuje
let previousCameraPosition = null;
let previousControlsTarget = null;
let previousCameraZoom = null;


// TEXTURE LOADER
const loader = new THREE.TextureLoader();


// AUDIO
const listener = new THREE.AudioListener(); // our "ears"
camera.add( listener ); // add listener to camera

// AUDIO LOADER
// load a sound and set it as the Audio object's buffer
const audioLoader = new THREE.AudioLoader();

let pullAudioBuffer = null;
let putAudioBuffer = null;

function setupBookAudio(sound, buffer) {
    if (buffer) {
        sound.setBuffer(buffer);
    }
}

audioLoader.load( '/assets/sounds/slide-out-book.wav', ( buffer ) => {
	pullAudioBuffer = buffer;

    books.forEach((book) => {
        setupBookAudio(book.userData.pullSound, pullAudioBuffer);
    });
});

audioLoader.load( '/assets/sounds/book-close-2.wav', ( buffer ) => {
	putAudioBuffer = buffer;

    books.forEach((book) => {
        setupBookAudio(book.userData.putSound, putAudioBuffer);
    });
});



// DIMENSIONS
const COVER_WIDTH = 0.8;
const COVER_HEIGHT = 1.2;
const SPACING_X = 0.2;
const SPACING_Y = 0.5;
const SHELF_THICKNESS = 0.1;
const NUM_SHELVES = 6;

const SPINE_WIDTH = 0.18;
const SPINE_HEIGHT = COVER_HEIGHT;
const SPINE_DEPTH = 0.8;

const BOOKS_PER_ROW = 25;
const TOP_PADDING = 1.2;

const totalHeight = NUM_SHELVES * (COVER_HEIGHT + SPACING_Y + SHELF_THICKNESS) + 2;


// SHELVES
const shelfMaterial = new THREE.MeshStandardMaterial({
    color: 0x3e2e1c,
    roughness: 0.75,
    metalness: 0
});
const shelfGeometry = new THREE.BoxGeometry(
    (SPINE_WIDTH + SPACING_X) * BOOKS_PER_ROW,
    SHELF_THICKNESS,
    SPINE_DEPTH + 0.1
);

const shelves = [];
const shelfSpacing = COVER_HEIGHT + SPACING_Y + SHELF_THICKNESS;

for (let i = 0; i < NUM_SHELVES; i++) {
    const shelf = new THREE.Mesh(shelfGeometry, shelfMaterial);
    
    //shelf.position.y = totalHeight / 2 - i * shelfSpacing;
    shelf.position.y = camera.top - TOP_PADDING - (COVER_HEIGHT / 2) - (SHELF_THICKNESS / 2) - i * shelfSpacing;

    scene.add(shelf);
    shelves.push(shelf);
}

// SPINE TEXTURE
function createSpineTexture(title, bgColor = '#8b5a2b', textColor = '#ffffff') {
    
    const canvas = document.createElement('canvas');
    canvas.width = 512; //256 old
    canvas.height = 2048; //1024 old
     //326.50

    const ctx = canvas.getContext('2d');

    // background
    ctx.fillStyle = bgColor;
    ctx.fillRect(0, 0, canvas.width, canvas.height);

    // text
    ctx.fillStyle = textColor;
    ctx.font = 'bold 120px Segoe UI'; //60 old
    ctx.textAlign = 'center';
    ctx.textBaseline = 'middle';

    // rotate for vertical text
    //use save and restore?
    ctx.translate(canvas.width / 2, canvas.height / 2);
    ctx.rotate(-Math.PI / 2);
    ctx.fillText(title, 0, 0); 
    //
    
    const texture = new THREE.CanvasTexture(canvas);
    texture.anisotropy = renderer.capabilities.getMaxAnisotropy();
    texture.needsUpdate = true;
    return texture;

    //return new THREE.CanvasTexture(canvas);
}


// PAPER TEXTURE
function createPaperTexture() {

    const canvas = document.createElement('canvas');
    canvas.width = 512; 
    canvas.height = 512; 

    const ctx = canvas.getContext('2d');

    // osnovna boja papira
    ctx.fillStyle = '#e1cb8d'; // #f5eabc
    ctx.fillRect(0, 0, canvas.width, canvas.height);

    // ivice stranica detalji
    ctx.strokeStyle = 'rgba(104, 87, 53, 0.66)';
    ctx.lineWidth = 1;

    for (let x = 5; x < canvas.height; x += 12) { // nacrtaj liniju svakih 10 piksela
        ctx.beginPath();
        ctx.moveTo(x, 0);
        ctx.lineTo(x, canvas.height); // preko celog kanvasa
        ctx.stroke();
    }

    return new THREE.CanvasTexture(canvas);
}



// BOOKS

const books = [];

BOOKSHELF_DATA.forEach((book, index) => {
    //const texture = loader.load(`/assets/covers/${book.cover_image}`);
    //texture.minFilter = THREE.LinearFilter; 
    //texture.magFilter = THREE.LinearFilter;

    const color = new THREE.Color().setHSL(Math.random(), 0.5, 0.5);

    // TODO: ako hoces flatShading kasnije, 
    // koristi MeshLambertMaterial ili MeshStandardMaterial
    // BACK COVER
    const backCoverMaterial = new THREE.MeshStandardMaterial({
        color: color,
        roughness: 0.85,
        metalness: 0
        //flatShading: true
    });

    // SPINE
    const spineMaterial = new THREE.MeshStandardMaterial({
        map: createSpineTexture(book.title),
        color: color,   // keeps pastel tint
        roughness: 0.25,
        metalness: 0
    });

    // COVER
    const coverTexture = loader.load(`/assets/covers/${book.cover_image}`);
    
    coverTexture.minFilter = THREE.LinearMipmapLinearFilter;
    coverTexture.magFilter = THREE.LinearFilter;
    coverTexture.anisotropy = 4; 

    const coverMaterial = new THREE.MeshStandardMaterial({
        map: coverTexture,
        roughness: 0.3,
        metalness: 0
        //envMapIntensity: 0.2
    });

    // PAPER EDGES
    const paperTexture = createPaperTexture();
    const paperMaterial = new THREE.MeshStandardMaterial({
        map: paperTexture,
        //color: 0xf5ecd4,
        roughness: 0.9,
        metalness: 0
    }); 

    // kada knjiga stoji uvucena na polici
    const materials = [
        coverMaterial ?? baseMaterial,  // right
        backCoverMaterial,              // left
        paperMaterial,                  // top
        paperMaterial,                  // bottom
        spineMaterial,                  // front
        paperMaterial                   // back
    ];


    const geometry = new THREE.BoxGeometry(
        SPINE_WIDTH,
        SPINE_HEIGHT,
        SPINE_DEPTH
    );

    const mesh = new THREE.Mesh(geometry, materials);

    // AUDIO
    const pullSound = new THREE.PositionalAudio(listener);
    const putSound = new THREE.PositionalAudio(listener);
    
    pullSound.setVolume(0.5); // osnovna jacina
    pullSound.setRefDistance(2); // udaljenost pocetka smanjivanja jacine
    pullSound.setRolloffFactor(1); // brzina opadanja
   
    putSound.setVolume(0.7); 
    putSound.setRefDistance(3);
    putSound.setRolloffFactor(1);


    mesh.add(pullSound);
    mesh.add(putSound);

    mesh.userData.pullSound = pullSound;
    mesh.userData.putSound = putSound;

    setupBookAudio(pullSound, pullAudioBuffer);
    setupBookAudio(putSound, putAudioBuffer);
    

    // for pulling out books
    mesh.userData.isBook = true;
    //mesh.userData.originalPosition = mesh.position.clone();
    //mesh.userData.originalRotation = mesh.rotation.clone();
    books.push(mesh);

    /*const material = new THREE.MeshBasicMaterial({ map: texture, transparent: true });
    const geometry = new THREE.PlaneGeometry(COVER_WIDTH, COVER_HEIGHT);
    const mesh = new THREE.Mesh(geometry, material);
    */

    const shelfIndex = Math.floor(index / BOOKS_PER_ROW); // which shelf
    const col = index % BOOKS_PER_ROW; //which column on that shelf
    const isOffset = col % 2 === 1; // every other is offset
    mesh.userData.isOffset = isOffset;

    if (shelfIndex >= NUM_SHELVES) return; // skip books exceeding shelf limit

    // position book on top of the shelf
    const shelfY = shelves[shelfIndex].position.y;
    mesh.position.y = shelfY + SHELF_THICKNESS / 2 + COVER_HEIGHT / 2;
    mesh.position.x = (col - (BOOKS_PER_ROW - 1) / 2) * (SPINE_WIDTH + SPACING_X);
    mesh.position.z = 0;

    mesh.userData.originalPosition = mesh.position.clone();
    mesh.userData.originalRotation = mesh.rotation.clone();

    scene.add(mesh);
});


// ENTER EXAMINE MODE/STATE
function enterExamineMode() {

    if (!contextMenuBook || currentlyExaminedBook) {
        return;
    }

    //const examinedBook = contextMenuBook; // lokalna promenljiva cuva vrednost 
    currentlyExaminedBook = contextMenuBook;


    // sacuvaj trenutni pogled kamere
    previousCameraPosition = camera.position.clone();
    previousControlsTarget = controls.target.clone();
    previousCameraZoom = camera.zoom;

    
    contextMenu.style.display = 'none';
    contextMenuBook = null;

    console.log('Examining book: ', currentlyExaminedBook);

    // BOOK
    gsap.to(currentlyExaminedBook.position, {
        x: 0,
        y: 0,
        z: 5,
        duration: 0.8,
        ease: "power2.out"
    });

    // CAMERA POS
    gsap.to(camera.position, {
        x: 0,
        y: 0,
        z: 10,
        duration: 0.8,
        ease: "power2.out",
        onUpdate: () => {
            controls.update();
        }
    });

    // CAMERA TARGET
    gsap.to(controls.target, {
        x: 0,
        y: 0,
        z: 0,
        duration: 0.8,
        ease: "power2.out",
        onUpdate: () => {
            controls.update();
        }
    });

    // ZOOM
    gsap.to(camera, {
        zoom: 4,
        duration: 0.8,
        ease: "power2.out",
        onUpdate: () => {
            camera.updateProjectionMatrix();
        }
    });

}

// EXIT EXAMINE MODE/STATE
function exitExamineMode() {
    if (!currentlyExaminedBook) {
        return;
    }

    currentlyExaminedBook = null;
    console.log('Currently examined:', currentlyExaminedBook);

    // vrati kameru na prethodno stanje/ unzoom
    gsap.to(camera.position, {
        x: previousCameraPosition.x,
        y: previousCameraPosition.y,
        z: previousCameraPosition.z,
        duration: 0.8,
        ease: "power2.out",
        onUpdate: () => {
            controls.update();
        }
    });

    // vrati target
    gsap.to(controls.target, {
        x: previousControlsTarget.x,
        y: previousControlsTarget.y,
        z: previousControlsTarget.z,
        duration: 0.8,
        ease: "power2.out",
        onUpdate: () => {
            controls.update();
        }
    });

    // vrati zoom
    gsap.to(camera, {
        zoom: previousCameraZoom,
        duration: 0.8,
        ease: "power2.out",
        onUpdate: () => {
            camera.updateProjectionMatrix();
        }
    });

}

// RETURN TO SHELF
function returnBookToShelf(book) {

    if (!book || !book.userData.isPulledOut) {
        return;
    }

    if (!book.userData.putSound.isPlaying) {
        //book.userData.putSound.setPlaybackRate(1.1); // izbrisi ako je zvuk prebrz
        book.userData.putSound.play();
    }

    gsap.to(book.position, {
        x: book.userData.originalPosition.x,
        y: book.userData.originalPosition.y,
        z: book.userData.originalPosition.z,
        duration: 0.7, // 0.6 ako treba bolje uskladiti sa zvukom, da bude malo brza animacija
        ease: "power1.out"
    });

    gsap.to(book.rotation, {
        x: book.userData.originalRotation.x,
        y: book.userData.originalRotation.y,
        z: book.userData.originalRotation.z,
        duration: 0.7, // 0.6 ako treba bolje uskladiti sa zvukom
        ease: "power1.out",
        onComplete: () => {
            renderer.domElement.style.cursor = 'grab';
        }
    });

    book.userData.isPulledOut = false;

}


// on click
renderer.domElement.addEventListener('click', (event) => {

    contextMenu.style.display = 'none';
    contextMenuBook = null;
    
    const rect = renderer.domElement.getBoundingClientRect();
    mouse.x = ((event.clientX - rect.left) / rect.width) * 2 - 1;
    mouse.y = -((event.clientY - rect.top) / rect.height) * 2 + 1;

    raycaster.setFromCamera(mouse, camera);

    const intersects = raycaster.intersectObjects(books);
    if (intersects.length === 0) {
        return; // no book clicked
    }

    const clickedBook = intersects[0].object;
    renderer.domElement.style.cursor = 'grabbing';
    
    if (clickedBook.userData.isPulledOut) {

        // ako je knjiga na koju se klikne ista kao i ona koja se istrazuje, dobijamo true, 
        // sto znaci: vrati je na policu i postavi examined knjigu na null (jer knjiga je vracena nista se ne istrazuje, sada moze druga knjiga da dodje na red)
        // ako ako je knjiga na koju se klikne drugacija od one koja se istrazuje, dobijamo false
        // sto znaci ne ulazi u IF i examined je i dalje zauzet, ne mozemo da istrazujemo nijednu drugu knjigu dok ovu ne vratimo
        if (clickedBook === currentlyExaminedBook) {
            exitExamineMode();
        }

        returnBookToShelf(clickedBook);
    
        return; // already pulled out
    }

    clickedBook.userData.isPulledOut = true;

    if (!clickedBook.userData.pullSound.isPlaying) {
        clickedBook.userData.pullSound.play();
    }

    // pull out on z and rotate
    gsap.to(clickedBook.position, {
        z: clickedBook.userData.isOffset ? 1.5 : 1.2,
        duration: 0.6,
        ease: "power1.out"
        
    });

    gsap.to(clickedBook.rotation, {
        y: -Math.PI / 2,
        duration: 0.6,
        ease: "power1.out",
        onComplete: () => {
            renderer.domElement.style.cursor = 'grab';
          //  clickedBook.userData.isPulledOut = false;
        }
    });
});


// on right click
renderer.domElement.addEventListener('contextmenu', (event) => {
    event.preventDefault();

    console.log("Context menu event");

    const rect = renderer.domElement.getBoundingClientRect();

    mouse.x = ((event.clientX - rect.left) / rect.width) * 2 - 1;
    mouse.y = -((event.clientY - rect.top) / rect.height) * 2 + 1;
    
    console.log('Mouse:', mouse.x, mouse.y);

    raycaster.setFromCamera(mouse, camera);
/////////////////
    const intersects = raycaster.intersectObjects(books);
    console.log("Intersects:", intersects.length);

    if (intersects.length === 0) {
        return;
    }

    const rightClickedBook = intersects[0].object;

    // ako je neka knjiga vec u examine modu, ne prikazuj context meni za ostale
    if (currentlyExaminedBook && rightClickedBook !== currentlyExaminedBook) {
        contextMenu.style.display = 'none';
        contextMenuBook = null;
        return;
    }

    contextMenuBook = rightClickedBook;

    //
    if (rightClickedBook === currentlyExaminedBook) {
        examineButton.style.display = 'none';
        examineMenuOptions.style.display = 'block';
    } else {
        examineButton.style.display = 'block';
        examineMenuOptions.style.display = 'none';
    }

    contextMenu.style.left = `${event.clientX - rect.left}px`;
    contextMenu.style.top = `${event.clientY - rect.top}px`;

    contextMenu.style.display = 'block';

    
    console.log('Right-cliked book:', rightClickedBook);

});

// examine button click
examineButton.addEventListener('click', () => {
    enterExamineMode();
});

// exit button
exitExamineButton.addEventListener('click', () => {
    if (!currentlyExaminedBook) {
        return;
    }

    const examinedBook = currentlyExaminedBook;
    // cuva pre postavljanja na null

    exitExamineMode(); // postavi currently exam. na null
    returnBookToShelf(examinedBook);

    contextMenu.style.display = 'none';
    contextMenuBook = null;
});


// RENDER LOOP
function animate() {
    requestAnimationFrame(animate);
    controls.update();

    //SCROLL -> pravi problem onesposobljava panning
    /*
    const maxY = camera.top - TOP_PADDING;
    const minY = camera.bottom + COVER_HEIGHT;

    camera.position.y = THREE.MathUtils.clamp(
        camera.position.y,
        minY,
        maxY
    );
    */

    renderer.render(scene, camera);
}
animate();


// resize
window.addEventListener('resize', () => {
    const width = container.clientWidth;
    const height = container.clientHeight || 600;
    renderer.setSize(width, totalHeight * 100);
    container.style.height = '600px';

    camera.left = width / -200;
    camera.right = width / 200;
    camera.top = 1;
    camera.bottom = -totalHeight;
    camera.updateProjectionMatrix();

});


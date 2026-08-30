import * as THREE from '/assets/libs/three/three.module.min.js';
import { OrbitControls } from '/assets/libs/three/examples/jsm/controls/OrbitControls.js';


const container = document.getElementById('bookshelf-container');
const width = container.clientWidth;
const height = container.clientHeight || 600;

// SCENE
const scene = new THREE.Scene();
scene.background = new THREE.Color(0xffffff);

// CAMERA
const camera = new THREE.OrthographicCamera(
    width / -200, width / 200,
    height / 200, height / -200,
    0.1, 1000
);
camera.position.z = 10;

// LIGHT  ---- za kasnije
const ambientLight = new THREE.AmbientLight(0xffffff, 0.6);
scene.add(ambientLight);

const directionalLight = new THREE.DirectionalLight(0xffffff, 3);
directionalLight.position.set(2, 5, 6); // staro 3,5,2  
scene.add(directionalLight);

/*const coverLight = new THREE.PointLight(0xffffff, 0, 20);
coverLight.position.set(5, 9, 10);
scene.add(coverLight);*/

const fillLight = new THREE.DirectionalLight(0xffffff, 0.4);
fillLight.position.set(-5, 2, -2);
scene.add(fillLight);


// RENDERER
const renderer = new THREE.WebGLRenderer({ antialias: true });
renderer.setSize(width, height);

renderer.setPixelRatio(Math.min(window.devicePixelRatio, 2));
container.appendChild(renderer.domElement);

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


const loader = new THREE.TextureLoader();

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
const shelfMaterial = new THREE.MeshBasicMaterial({ color: 0x3e2e1c });
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

function createPaperTexture() {

    const canvas = document.createElement('canvas');
    canvas.width = 512; 
    canvas.height = 512; 

    const ctx = canvas.getContext('2d');

    // osnovna boja papira
    ctx.fillStyle = '#fae5ab'; // #f5eabc
    ctx.fillRect(0, 0, canvas.width, canvas.height);

    // ivice stranica detalji
    ctx.strokeStyle = 'rgba(120, 100, 60, 0.66)';
    ctx.lineWidth = 1;

    for (let x = 5; x < canvas.height; x += 10) { // nacrtaj liniju svakih 10 piksela
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
        roughness: 0.2,
        metalness: 0
    });

    // COVER
    const coverTexture = loader.load(`/assets/covers/${book.cover_image}`);
    const coverMaterial = new THREE.MeshStandardMaterial({
        map: coverTexture,
        roughness: 0.05,
        metalness: 0 
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


   /* const material = new THREE.MeshBasicMaterial({
        map: spineTexture,
        color: color,
        flatShading: true
    });*/

    const geometry = new THREE.BoxGeometry(
        SPINE_WIDTH,
        SPINE_HEIGHT,
        SPINE_DEPTH
    );

    const mesh = new THREE.Mesh(geometry, materials);

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


// on click
renderer.domElement.addEventListener('click', (event) => {
    
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
        gsap.to(clickedBook.position, {
            x: clickedBook.userData.originalPosition.x,
            y: clickedBook.userData.originalPosition.y,
            z: clickedBook.userData.originalPosition.z,
            duration: 0.6,
            ease: "power1.out"
        });

        gsap.to(clickedBook.rotation, {
            x: clickedBook.userData.originalRotation.x,
            y: clickedBook.userData.originalRotation.y,
            z: clickedBook.userData.originalRotation.z,
            duration: 0.6,
            ease: "power1.out",
            onComplete: () => {
            renderer.domElement.style.cursor = 'grab';
        }
        });

        clickedBook.userData.isPulledOut = false;
    
        return; // already pulled out
    }

    clickedBook.userData.isPulledOut = true;

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


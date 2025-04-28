// public/js/desert.js

document.addEventListener('DOMContentLoaded', function () {
    // Create a container for Three.js; we attach it to a div with id "three-container"
    const container = document.getElementById('three-container');
  
    // Scene, camera, and renderer
    const scene = new THREE.Scene();
    scene.fog = new THREE.Fog(0xd2b48c, 10, 50); // sandy fog
  
    const camera = new THREE.PerspectiveCamera(75, window.innerWidth / window.innerHeight, 0.1, 1000);
    camera.position.set(0, 5, 15);
  
    const renderer = new THREE.WebGLRenderer({ antialias: true });
    renderer.setSize(window.innerWidth, window.innerHeight);
    renderer.setClearColor(0xd2b48c); // sand background color
    container.appendChild(renderer.domElement);
  
    // Adjust renderer on window resize
    window.addEventListener('resize', function () {
      camera.aspect = window.innerWidth / window.innerHeight;
      camera.updateProjectionMatrix();
      renderer.setSize(window.innerWidth, window.innerHeight);
    });
  
    // Create a plane to simulate the desert ground
    const geometry = new THREE.PlaneGeometry(100, 100, 50, 50);
    const material = new THREE.MeshLambertMaterial({ color: 0xf4a460 });
    const plane = new THREE.Mesh(geometry, material);
    plane.rotation.x = -Math.PI / 2;
    scene.add(plane);
  
    // Simple vertex displacement for dune effect
    geometry.vertices.forEach(vertex => {
      vertex.z = Math.random() * 0.5; 
    });
    geometry.computeVertexNormals();
  
    // Lighting
    const ambientLight = new THREE.AmbientLight(0xffffff, 0.6);
    scene.add(ambientLight);
    const directionalLight = new THREE.DirectionalLight(0xffffff, 0.8);
    directionalLight.position.set(10, 20, 10);
    scene.add(directionalLight);
  
    // Render loop
    function animate() {
      requestAnimationFrame(animate);
      renderer.render(scene, camera);
    }
    animate();
  });
  
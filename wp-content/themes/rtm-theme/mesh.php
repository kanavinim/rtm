
        <style>

            #renderCanvas {
               position: relative;
            overflow: hidden;
            width: 100%;
            height: 100%;
            margin: 0;
            padding: 0;
            touch-action: none;
            -ms-touch-action: none;
            display: block;
            border: 0;
            outline: 0;
            }
        </style>
        <!-- HEADER -->
        <canvas id="renderCanvas"></canvas>

    <script>
        const canvas = document.getElementById("renderCanvas"); // Get the canvas element
        const engine = new BABYLON.Engine(canvas, true); // Generate the BABYLON 3D engine
        const createScene = function () {

            const scene = new BABYLON.Scene(engine);

            const camera = new BABYLON.ArcRotateCamera("Camera", 1.5708, 1.5708, 13.6995, new BABYLON.Vector3(0, 0, 0), scene);
                // This positions the camera
            camera.setPosition(new BABYLON.Vector3(50, 10, 0));

            // This attaches the camera to the canvas
            camera.attachControl(canvas, true);

            const light = new BABYLON.HemisphericLight("light", new BABYLON.Vector3(0, 3, 0), scene);
            light.intensity = 1.7;

            BABYLON.SceneLoader.ImportMeshAsync("", "https://rtm-a.ru/", "scene.babylon", this._scene);

            return scene;
        };
        const scene = createScene(); //Call the createScene function
        // Register a render loop to repeatedly render the scene
        engine.runRenderLoop(function () {
                scene.render();
        });
        // Watch for browser/canvas resize events
        window.addEventListener("resize", function () {
                engine.resize();
        });
    </script>
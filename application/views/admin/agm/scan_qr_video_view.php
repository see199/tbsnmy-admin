<link href="<?= base_url(); ?>assets/qrscan/style.css" rel="stylesheet">
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/webrtc-adapter/3.3.3/adapter.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/vue/2.1.10/vue.min.js"></script>
<script type="text/javascript" src="https://rawgit.com/schmich/instascan-builds/master/instascan.min.js"></script>

<div id="app">
  <div class="sidebar">
    <section class="cameras">
      <h2>Cameras</h2>
      <ul>
        <li v-if="cameras.length === 0" class="empty">No cameras found</li>
        <li v-for="camera in cameras">
          <span v-if="camera.id == activeCameraId" :title="formatName(camera.name)" class="active">{{ formatName(camera.name) }}</span>
          <span v-if="camera.id != activeCameraId" :title="formatName(camera.name)">
            <a @click.stop="selectCamera(camera)">{{ formatName(camera.name) }}</a>
          </span>
        </li>
      </ul>
    </section>
    <section class="scans">
      <h2>Scans</h2>
      <ul v-if="scans.length === 0">
        <li class="empty">No scans yet</li>
      </ul>
      <transition-group name="scans" tag="ul">
        <li v-for="scan in scans" :key="scan.date" :title="scan.content">{{ scan.date }} -- {{ scan.content }}</li>
      </transition-group>
    </section>
  </div>
  <div class="preview-container">2222
    <div v-if="responseData">
      <h2>Response Data:</h2>
      <pre>{{ responseData }}</pre> <!-- Display the response data -->
    </div>3333
    <video id="preview"></video>
  </div>
</div>








<script type="text/javascript" src="<?= base_url(); ?>assets/qrscan/app.js"></script>
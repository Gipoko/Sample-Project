<template>
  <div class="p-6 bg-gray-50 min-h-screen">

      <!-- TOAST -->
      <div v-if="showToast" class="fixed top-6 left-1/2 transform -translate-x-1/2 z-50">
        <div class="px-6 py-3 rounded-xl shadow-xl text-sm text-white transition-all duration-300"
        :class="toastType === 'success' ? 'bg-green-600' : 'bg-red-600'">
            {{ toastMessage }}
        </div>
      </div>

    <!-- HEADER -->
    <div class="flex justify-between items-center mb-4">
      <h1 class="text-xl font-semibold text-gray-800">
        Documents
      </h1>

      <button
        @click="openAddModal"
        class="px-4 py-2 bg-black text-white rounded-lg hover:bg-gray-800 transition"
      >
        + Add Document
      </button>
    </div>

    <!-- TABLE CARD -->
    <div class="bg-white rounded-xl shadow-sm border overflow-hidden">

      <table class="w-full text-sm">

        <thead class="bg-gray-100 text-gray-600">
          <tr>
            <th class="p-3 text-left">Title</th>
            <th class="p-3 text-left">File</th>
            <th class="p-3 text-left">Uploaded By</th>
            <th class="p-3 text-left">Created</th>
            <th class="p-3 text-left">Updated</th>
            <th class="p-3 text-left">Actions</th>
          </tr>
        </thead>

        <tbody class="divide-y">

          <tr v-for="doc in documents" :key="doc.id" class="hover:bg-gray-50">

            <td class="p-3 font-medium text-gray-800">
              {{ doc.title }}
            </td>

            <td class="p-3">
              <a
                :href="'/storage/' + doc.file_path"
                target="_blank"
                class="px-3 py-1 text-xs bg-blue-50 text-blue-600 rounded-md hover:bg-blue-100 transition"
              >
                View
              </a>
            </td>

            <td class="p-3 text-gray-700">
              {{ doc.uploader_name }}
            </td>

            <td class="p-3 text-gray-500">
              {{ doc.created_at }}
            </td>

            <td class="p-3 text-gray-500">
              {{ doc.updated_at }}
            </td>

            <!-- ACTIONS -->
            <td class="p-3 flex gap-2">

              <button
                @click="edit(doc)"
                class="px-3 py-1 text-xs border border-gray-300 rounded-md hover:bg-gray-100 transition"
              >
                Edit
              </button>

              <button
                @click="remove(doc.id)"
                class="px-3 py-1 text-xs bg-red-50 text-red-600 border border-red-200 rounded-md hover:bg-red-100 transition"
              >
                Delete
              </button>

            </td>

          </tr>

        </tbody>

      </table>
    </div>

    <!-- MODAL -->
    <div
      v-if="showModal"
      class="fixed inset-0 bg-black/40 flex items-center justify-center"
    >
      <div class="bg-white w-96 rounded-xl shadow-lg p-6">

        <h2 class="text-lg font-semibold mb-4 text-gray-800">
          {{ editMode ? 'Update' : 'Add' }} Document
        </h2>

        <input
          v-model="form.title"
          placeholder="Title"
          class="w-full mb-3 p-2 border rounded-md focus:ring-2 focus:ring-black outline-none"
        />

        <input type="file" @change="handleFile" class="mb-4" />

        <div class="flex justify-end gap-2">

          <button
            @click="closeModal"
            class="px-3 py-2 text-sm rounded-md border hover:bg-gray-100"
          >
            Cancel
          </button>

          <button
            @click="save"
            class="px-3 py-2 text-sm bg-black text-white rounded-md hover:bg-gray-800"
          >
            Save
          </button>

        </div>

      </div>
    </div>

  </div>
</template>

<!-- <script>
import axios from 'axios';

export default {
  data() {
    return {
      documents: [],
      showModal: false,
      editMode: false,
      alert: '',
      form: {
        id: null,
        title: '',
        file: null,
      }
    };
  },

  mounted() {
    this.fetchDocs();
  },

  methods: {

    fetchDocs() {
      axios.get('/documents').then(res => {
        this.documents = res.data;
      });
    },

    openAddModal() {
      this.editMode = false;
      this.form = { title: '', file: null };
      this.showModal = true;
    },

    handleFile(e) {
      this.form.file = e.target.files[0];
    },

    save() {
      let formData = new FormData();
      formData.append('title', this.form.title);
      if (this.form.file) {
        formData.append('file', this.form.file);
      }

      let url = this.editMode
        ? `/documents/${this.form.id}`
        : '/documents';

      axios.post(url, formData).then(() => {
        this.showAlert('Saved successfully!');
        this.fetchDocs();
        this.closeModal();
      });
    },

    edit(doc) {
      this.editMode = true;
      this.form = { ...doc };
      this.showModal = true;
    },

  
  remove(id) {
  const isConfirmed = window.confirm(
    "This will permanently delete this document. Continue?"
  );

  if (!isConfirmed) return;

  axios.delete(`/documents/${id}`).then(() => {
    this.showAlert('Deleted successfully!');
    this.fetchDocs();
  });
},

    closeModal() {
      this.showModal = false;
    },

    showAlert(msg) {
      this.alert = msg;
      setTimeout(() => this.alert = '', 3000);
    }
  }
};
</script> -->
<script>
import axios from 'axios';

export default {
  data() {
    return {
      documents: [],
      showModal: false,
      editMode: false,

      // TOAST (ShadCN style)
      showToast: false,
      toastMessage: '',
      toastType: 'success',

      form: {
        id: null,
        title: '',
        file: null,
      }
    };
  },

  mounted() {
    this.fetchDocs();
  },

  methods: {

    // . FETCH .
    fetchDocs() {
      axios.get('/documents').then(res => {
        this.documents = res.data;
      });
    },

    // . MODAL .
    openAddModal() {
      this.editMode = false;
      this.form = { id: null, title: '', file: null };
      this.showModal = true;
    },

    closeModal() {
      this.showModal = false;
    },

    handleFile(e) {
      this.form.file = e.target.files[0];
    },

    // . SAVE .
    save() {
      let formData = new FormData();
      formData.append('title', this.form.title);

      if (this.form.file) {
        formData.append('file', this.form.file);
      }

      let url = this.editMode
        ? `/documents/${this.form.id}`
        : '/documents';

      axios.post(url, formData)
        .then(() => {
          this.showToastMsg('Saved successfully!', 'success');
          this.fetchDocs();
          this.closeModal();
        })
        .catch(() => {
          this.showToastMsg('Something went wrong!', 'error');
        });
    },

    // . EDIT .
    edit(doc) {
      this.editMode = true;

      // IMPORTANT FIX: don't blindly spread file_path into file
      this.form = {
        id: doc.id,
        title: doc.title,
        file: null
      };

      this.showModal = true;
    },

    // . DELETE .
    remove(id) {
      const isConfirmed = window.confirm(
        "This will permanently delete this document. Continue?"
      );

      if (!isConfirmed) return;

      axios.delete(`/documents/${id}`)
        .then(() => {
          this.showToastMsg('Deleted successfully!', 'destructive');
          this.fetchDocs();
        })
        .catch(() => {
          this.showToastMsg('Delete failed!', 'error');
        });
    },

    // . TOAST .
    showToastMsg(message, type = 'success') {
      this.toastMessage = message;
      this.toastType = type;
      this.showToast = true;

      setTimeout(() => {
        this.showToast = false;
      }, 3000);
    }

  }
};
</script>
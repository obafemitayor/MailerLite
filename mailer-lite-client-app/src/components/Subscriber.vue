<template>
  <div class="hello">
    <button
      type="button"
      v-on:click="
        showSubcribe = true;
        showSubcribeField = false;
        showSubcribedFields = false;
      "
    >
      Subscribe To Mailer Lite
    </button>
    <button
      type="button"
      v-on:click="
        showSubcribe = false;
        showSubcribeField = true;
        showSubcribedFields = false;
      "
    >
      Subscribe To Field
    </button>
    <button
      type="button"
      v-on:click="
        showSubcribe = false;
        showSubcribeField = false;
        showSubcribedFields = true;
      "
    >
      View Subscribed Fields
    </button>

    <div class="container" v-if="showSubcribe">
      <div class="form-group">
        <label for="email">Email address:</label>
        <input
          type="email"
          v-model="newSubscriber.email"
          placeholder="Enter Email"
        />
      </div>
      <div class="form-group">
        <label for="pwd">Name:</label>
        <input
          type="text"
          v-model="newSubscriber.name"
          placeholder="Enter Your Name"
        />
      </div>
      <button
        type="button"
        class="btn btn-default"
        v-on:click="subscribeUser()"
      >
        Submit
      </button>
    </div>

    <div class="container" v-if="showSubcribeField">
      <div class="form-group">
        <label for="email">Email address:</label>
        <input
          type="email"
          v-model="newFieldForSubscriber.email"
          placeholder="Enter Email"
        />
      </div>
      <div class="form-group">
        <label for="pwd">Name:</label>
        <input
          type="text"
          v-model="newFieldForSubscriber.title"
          placeholder="Enter Title"
        />
      </div>
      <div class="form-group">
        <label for="pwd">Name:</label>
        <input
          type="text"
          v-model="newFieldForSubscriber.type"
          placeholder="Enter Type"
        />
      </div>
      <button
        type="button"
        class="btn btn-default"
        v-on:click="subscribeUserToField()"
      >
        Submit
      </button>
    </div>

    <div class="container" v-if="showSubcribedFields">
      <div class="form-group">
        <label for="email">Email address:</label>
        <input type="email" v-model="searchEmail" placeholder="Enter Email" />
      </div>
      <button
        type="button"
        class="btn btn-default"
        v-on:click="getSubscribedFieldsForUser(0)"
      >
        Get Subscribed Fields
      </button>

      <table class="table table-striped table-bordered">
        <thead>
          <tr>
            <th>Field Title</th>
            <th>Field Type</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="field in subscribedFields" :key="field.id">
            <td>{{ field.title }}</td>
            <td>{{ field.type }}</td>
          </tr>
        </tbody>
      </table>
      <b-pagination
        v-model="pageIndex"
        :total-rows="totalCount"
        :per-page="pageSize"
        aria-controls="itemList"
        align="center"
        @page-click="getSubscribedFieldsForUser"
      ></b-pagination>
    </div>
  </div>
</template>

<script>
import axios from 'axios'
import md5 from 'md5'

export default {
  name: 'Subscriber',
  data () {
    return {
      token: md5('Secret_Key'),
      newSubscriber: {
        email: '',
        name: ''
      },
      newFieldForSubscriber: {
        email: '',
        title: '',
        type: ''
      },
      searchEmail: '',
      subscribedFields: null,
      showSubcribe: true,
      showSubcribeField: false,
      showSubcribedFields: false,
      totalCount: 0,
      pageSize: 10,
      pageIndex: 1
    }
  },
  methods: {
    async subscribeUser () {
      let result = null
      try {
        let endpoint = 'http://localhost:8000/api/addSubscriber'
        const config = {
          headers: {
            authToken: this.token
          }
        }
        result = await axios.post(endpoint, this.newSubscriber, config)
        alert(result.data)
      } catch (error) {
        console.log(error)
        alert('An Error Occurred, Please Contact Admin')
      }
    },
    async subscribeUserToField () {
      let result = null
      try {
        let endpoint = 'http://localhost:8000/api/addSubscriberField'
        const config = {
          headers: {
            authToken: this.token
          }
        }
        result = await axios.post(endpoint, this.newFieldForSubscriber, config)
        alert(result.data)
      } catch (error) {
        console.log(error)
        alert('An Error Occurred, Please Contact Admin')
      }
    },
    async getSubscribedFieldsForUser (pageindex) {
      let result = null
      try {
        let endpoint = `http://localhost:8000/api/getSubscribersField?email=${this.searchEmail}&pageindex=${pageindex}&pagesize=${this.pageSize}`
        const config = {
          headers: {
            authToken: this.token
          }
        }
        result = await axios.get(endpoint, config)
        console.log('result is ' + JSON.stringify(result))
        this.totalCount = result.data.total_count
        this.subscribedFields = result.data.records
        alert('Records Gotten')
      } catch (error) {
        console.log(error)
        alert('An Error Occurred, Please Contact Admin')
      }
    }
  }
}
</script>

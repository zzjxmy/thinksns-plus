<style lang="css" module>
    .container {
        padding: 15px;
    }
    .loadding {
        text-align: center;
        font-size: 42px;
        padding-top: 100px;
    }
    .loaddingIcon {
        animation-name: "TurnAround";
        animation-duration: 1.4s;
        animation-timing-function: linear;
        animation-iteration-count: infinite;
    }
    .image {
        max-width:200px;
        margin-bottom: 10px;
    }
</style>

<template>
<div :class="$style.container">
    <!-- 加载动画 -->
    <div v-show="loadding" :class="$style.loadding">
        <span class="glyphicon glyphicon-refresh" :class="$style.loaddingIcon"></span>
    </div>
    <div class="panel panel-default" v-show="!loadding">
      <div class="panel-heading">
        站点配置
      </div>
      <div class="panel-body">
        <div class="form-horizontal">
          <div class="col-md-8 col-md-offset-2">
            <div class="form-group">
              <label class="control-label col-md-2">站点状态</label>
              <div class="col-md-6">
                <label class="radio-inline">
                  <input type="radio" value="1" v-model="site.status"> 开启
                </label>
                <label class="radio-inline">
                  <input type="radio" value="0" v-model="site.status"> 关闭
                </label>
              </div>
              <div class="col-md-4">
                <span class="help-block">站点开启与关闭，请谨慎操作</span>
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-md-2">关闭原因</label>
              <div class="col-md-6">
                <input type="text" class="form-control" v-model="site.off_reason">
              </div>
              <div class="col-md-4">
                <span class="help-block">站点关闭，需要填写关闭原因</span>
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-md-2">APP端</label>
              <div class="col-md-6">
                <label class="radio-inline">
                  <input type="radio" value="1" v-model="site.app.status"> 开启
                </label>
                <label class="radio-inline">
                  <input type="radio" value="0" v-model="site.app.status"> 关闭
                </label>
              </div>
              <div class="col-md-4">
                <span class="help-block">APP端开启与关闭</span>
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-md-2">H5端</label>
              <div class="col-md-6">
                <label class="radio-inline">
                  <input type="radio" value="1" v-model="site.h5.status"> 开启
                </label>
                <label class="radio-inline">
                  <input type="radio" value="0" v-model="site.h5.status"> 关闭
                </label>
              </div>
              <div class="col-md-4">
                <span class="help-block">H5端开启与关闭</span>
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-md-2">预留呢称</label>
              <div class="col-md-6">
                <input type="text" class="form-control" v-model="site.reserved_nickname">
              </div>
              <div class="col-md-4">
                <span class="help-block">预留呢称，多个呢称用“,”分割</span>
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-md-2">客户邮箱</label>
              <div class="col-md-6">
                <input type="text" class="form-control" v-model="site.client_email">
              </div>
              <div class="col-md-4">
                <span class="help-block">客户邮箱</span>
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-md-2"></label>
              <div class="col-md-6">
                <button class="btn btn-primary btn-sm" @click.prevent="updateSiteConfigure">确认</button>
              </div>
              <div class="col-md-4">
                 <span class="text-success"  v-show="message.success">{{ message.success }}</span>
                 <span class="text-danger" v-show="message.error">{{ message.error }}</span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
</div>
</template>

<script>
import request, { createRequestURI } from '../../util/request';
const Site = {
    
    data: () => ({

        loadding: true,

        site: {
          status: 1,
          off_reason: '',
          app: {
            status: 1,
          },
          h5: {
            status: 1,
          },
          reserved_nickname: '',
          client_email: '',
        },

        message: {
          error: null,
          success: null,
        }


    }),

    methods: {
      
      getSiteConfigures () {
        request.get(
          createRequestURI('site/configures'),
          { validateStatus: status => status === 200 }
        ).then(response => {

          this.loadding = false;
          this.site = response.data;

        }).catch(({ response: { data: { errors = ['加载站点配置失败'] } = {} } = {} }) => {
          this.loadding = false;
          this.message.error = errors[0];
        });
      },

      updateSiteConfigure () {
        if (!this.validate()) {
          this.message.error = '请填写关闭站点的原因';
          return;
        }

        request.put(
          createRequestURI('update/site/configure'),
          { site: this.site },
          { validateStatus: status => status === 201 }
        ).then(({ data: { message: [ message ] = [] } }) => {
          
          this.message.success = message;

        }).catch(({ response: { data = {} } = {} }) => {

          let errors = data.errors;
          this.message.error = errors;

        });

      },

      validate () {
        let site = this.site;
        return (site.status == 0 && !site.off_reason) ? false : true;
      },

    },

    created () {

      this.getSiteConfigures();

    },

};
export default Site;
</script>
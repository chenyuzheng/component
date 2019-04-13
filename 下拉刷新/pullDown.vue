<template>
     <div class="pullDownBox" @touchstart="start($event)" ref="pullDownBox">
         <div class="notice" v-show="noticeFlag">
             {{noticeMessage}}

         </div>

         <div class="pullDownCon" ref="pullDownCon">
            <slot></slot>
         </div>

     </div>
</template>

<script>
    export default {
        name: 'hello',
        props:["callback","autoLoad","routerReplace"],
        data () {
            return {
                noticeMessage:"下拉刷新....",
                noticeFlag:false,
                starty:0,
                movey:0,
                pullDownBoxEle:null,
                pullDownConEle:null,
                pullFlag:true
            }
        },

        mounted(){

            this.pullDownBoxEle=this.$refs["pullDownBox"];
            this.pullDownConEle=this.$refs["pullDownCon"];

          if(this.autoLoad){
              if(this.callback){
                  this.callback(this);
              }
          }
        },
        methods:{
            start(e){

              if(!this.pullFlag){
                  return;
              }
               this.noticeMessage="下拉刷新...."
              this.pullFlag=false
              this.starty=e.touches[0].pageY;
              this.pullDownBoxEle.addEventListener("touchmove",this.move);
              document.body.addEventListener("touchend",this.end)
            },

            move(e){
                this.noticeFlag=true;
                this.movey=e.touches[0].pageY;
                if(this.movey-this.starty>20){
                    this.pullDownConEle.style.transition=null;
                    this.$root.routerPlace=false;
                    this.pullDownConEle.style.transform="translate(0,"+(this.movey-this.starty)/6+"px)"
                }
            },
            end(e){
                this.pullDownConEle.style.transition="transform 1s ease";
                this.pullDownConEle.style.transform="translate(0,30px)"

                this.noticeMessage="正在刷新..."
                this.pullDownBoxEle.removeEventListener("touchmove",this.move);
                document.body.removeEventListener("touchend",this.end);
                if(this.callback){
                    this.callback(this);
                }
            },

            over(){
                this.pullDownConEle.style.transform="translate(0,0)";
                this.noticeMessage="加载完毕!";
                var t=setTimeout(()=>{
                    clearTimeout(t);
                this.noticeFlag=false;
                this.pullFlag=true;
                },1000)
            }
        },
        destroyed(){
            this.$root.routerPlace=true;
        }
    }
</script>

<style scoped>
.pullDownBox{
    position: relative;width:100%;height:100%;
    background: red;
    overflow: scroll;
}
.notice{
    position: absolute;
    left:0;top:0;
    width:100%;height:30px;
    text-align: center; line-height: 30px;
 }
 .pullDownCon{

 }
</style>
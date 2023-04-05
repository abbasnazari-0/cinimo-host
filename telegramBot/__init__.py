from pyrogram import Client, filters
import os.path
import uvloop
import sys

path = os.path.dirname(__file__)
sys.path.append(path + "/functions")

import link_down_up
import upload_image
import upload_video
import utils
import message_conversation

uvloop.install()
# create 
# Replace YOUR_API_ID and YOUR_API_HASH with your own values
ApiId = 732757
ApiHash = "9572884801dd15dcbb4ae2104ee26573"
app = Client("my_bot", api_id=ApiId, api_hash=ApiHash, bot_token="5520564422:AAEAJFUsMSq28vnZPwwKlr6T3vXotvC6XPI")



# welcome text 
@app.on_message(filters.command('start'))
def start(client, message):
  message.reply("""سلام من رباتی هستم که میتونم لینک های شما رو دانلود کنم و براتون بفرستم \n
  لطفا لینک های خود را ارسال کنید 
  """)


# unfound message
@app.on_message(filters.private)
async def hello(client, message):
#   message =  str (message.text)# detect link
  if message.text:
    linkResult = utils.find_links(message.text) 
    if len(linkResult) > 0:
        await link_down_up.link_down_up(linkResult, message , path)
    else:
        await message_conversation.message_conversation(message)
        
  elif message.photo:
    await upload_image.upload_image(message, path, client)
  elif message.video:
    await upload_video.upload_video(message, path, client)
  else:
    await message.reply("متاسفانه پیام شما برای من قابل درک نیست")
    #  other else message
    # 

app.run()

import asyncio
import os.path
# import uvloop
import sys
import threading
path = os.path.dirname(__file__)
sys.path.append(path + "/functions")
import utils
import file_uploader

import tracemalloc
tracemalloc.start()
from pyrogram import Client, filters

import link_down_up
import upload_image
import upload_video
import utils
import message_conversation



# create 
# Replace YOUR_API_ID and YOUR_API_HASH with your own values
ApiId = 732757
ApiHash = "9572884801dd15dcbb4ae2104ee26573"
app = Client("my_bot", api_id=ApiId, api_hash=ApiHash, bot_token="5520564422:AAHyW_9W0cxlVmFh8b1FEbJOV65xtpize2w")

# welcome text  
@app.on_message(filters.command('start'))
async def start(client, message):
    await message.reply("سلام ❤️😍 \n من رباتی هستم که میتونم لینک های شما  و فایل های شما رو دانلود کنم و براتون بفرستم 🔗⬆️ \n لطفا لینک های خود را ارسال کنید  🔗 \n و یه فایل های عکسی و ویدیو رو مستقیم بفرستید🖼📹 \n من برات یه تگ میفرستم که میتونی اونو به اپ سینیمو اضافه کنی😎")


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
    my_thread = threading.Thread(target=upload_image.upload_image, args=(message, path, client))
    my_thread.start()

  elif message.video:
    my_thread = threading.Thread(target=upload_video.upload_video, args=(message, path, client))
    my_thread.start()
    
  else:
    await message.reply("متاسفانه پیام شما برای من قابل درک نیست")
    #  other else message
    # 

# uvloop.install()


app.run()
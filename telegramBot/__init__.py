import asyncio
import uvloop
import pyrogram
import os.path
import sys

path = os.path.dirname(__file__)
sys.path.append(path + "/functions")

import link_down_up
import upload_image
import upload_video
import utils
import message_conversation

# create 
# Replace YOUR_API_ID and YOUR_API_HASH with your own values
ApiId = 732757
ApiHash = "9572884801dd15dcbb4ae2104ee26573"
app = pyrogram.Client("my_bot", api_id=ApiId, api_hash=ApiHash, bot_token="5520564422:AAHyW_9W0cxlVmFh8b1FEbJOV65xtpize2w")

# welcome text  
@app.on_message(pyrogram.filters.command('start'))
def start(client, message):
    message.reply("سلام ❤️😍 \n من رباتی هستم که میتونم لینک های شما  و فایل های شما رو دانلود کنم و براتون بفرستم 🔗⬆️ \n لطفا لینک های خود را ارسال کنید  🔗 \n و یه فایل های عکسی و ویدیو رو مستقیم بفرستید🖼📹 \n من برات یه تگ میفرستم که میتونی اونو به اپ سینیمو اضافه کنی😎")

# unfound message
@app.on_message(pyrogram.filters.private)
async def hello(client, message):
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

if __name__ == '__main__':
    asyncio.set_event_loop_policy(uvloop.EventLoopPolicy())
    app.start()
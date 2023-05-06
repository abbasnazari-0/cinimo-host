import asyncio
import os.path
import redis
from pyrogram import Client, filters
from pyrogram.types import Message
from typing import Union
import sys

path = os.path.dirname(__file__)
sys.path.append(path + "/functions")
import utils
import file_uploader

redis_client = redis.Redis(host='localhost', port=6379, db=0)

# create 
# Replace YOUR_API_ID and YOUR_API_HASH with your own values
ApiId = 732757
ApiHash = "9572884801dd15dcbb4ae2104ee26573"
app = Client("my_bot", api_id=ApiId, api_hash=ApiHash, bot_token="5520564422:AAEY6gRCAXlkTcvKZ-UinwGbvZggwLhAYFg")

# welcome text  
@app.on_message(filters.command('start'))
async def start(client, message):
    await message.reply("سلام ❤️😍 \n من رباتی هستم که میتونم لینک های شما  و فایل های شما رو دانلود کنم و براتون بفرستم 🔗⬆️ \n لطفا لینک های خود را ارسال کنید  🔗 \n و یه فایل های عکسی و ویدیو رو مستقیم بفرستید🖼📹 \n من برات یه تگ میفرستم که میتونی اونو به اپ سینیمو اضافه کنی😎")


@app.on_message(filters.private)
async def process_message(client: Client, message: Message):
    if message.text:
        # Find links in the message
        links = utils.find_links(message.text)
        if len(links) > 0:
            for link in links:
                # Add link to the queue
                redis_client.lpush('download_queue', link)
            await message.reply('لینک‌های شما به صف دانلود اضافه شدند. به زودی فایل‌های مربوطه آپلود خواهند شد.')
        else:
            await message_conversation(message)
    elif message.photo:
        tag = utils.generate_string(20) + ".jpg"
        await message.reply(
            "عکس شناسایی شد \n عکس در حال آپلود است \n شما میتوانید تگ زیر را ثبت کنید \n \n \n <pre>"+ tag + "</pre>   ",
            quote=True
        )
        photo = message.photo
        file_id = photo.file_id
        npath = path + f"/files/images/" + file_id + ".jpg"
        await client.download_media(message=photo, file_name=npath)
    
        # Add file to the queue
        redis_client.lpush('upload_queue', (tag, npath, 'images'))
    
        await message.reply(f"آپلود عکس با موفقیت به صف آپلود اضافه شد. تگ فایل: {tag}", quote=True)
    
    elif message.video:
        tag = utils.generate_string(20) + ".mp4"
        await message.reply(
            "ویدیو شناسایی شد \n ویدیو در حال آپلود است \n شما میتوانید تگ زیر را ثبت کنید \n \n \n <pre>"+ tag + "</pre>   ",
            quote=True
        )
        video = message.video
        file_id = video.file_id
        npath = path + f"/files/videos/" + file_id + ".mp4"
        await client.download_media(message=video, file_name=npath)
    
        # Add file to the queue
        redis_client.lpush('upload_queue', (tag, npath, 'videos'))
    
        await message.reply(f"آپلود ویدیو با موفقیت به صف آپلود اضافه شد. تگ فایل: {tag}", quote=True)

async def upload_files():
    while True:
        # Get file from the queue
        file = redis_client.rpop('upload_queue')
        if file:
            tag, path, file_type = file.decode().split(',')
            await file_uploader.uploadFile(tag, path, file_type)
async def download_files():
    while True:
        # Get link from the queue
        link = redis_client.rpop('download_queue')
        if link:
            # Download the file
            filename = utils.download_file(link)
            # Send the file to the user
            await app.send_document(chat_id="USER ID", document=filename)

if __name__ == '__main__':
    # Start the upload and download processes
    asyncio.get_event_loop().run_until_complete(asyncio.gather(
        upload_files(),
        download_files(),
        app.run()
    ))
import sqlite3

async def message_conversation(message):
    await message.reply("متاسفانه پیام شما برای من قابل درک نیست")
    return 
    conn = sqlite3.connect('route.db')
    conn.execute("INSERT INTO COMPANY (ID,NAME,AGE,ADDRESS,SALARY) VALUES (4, 'Mark', 25, 'Rich-Mond ', 65000.00 )");
    conn.commit()
    print ("Records created successfully")
    conn.close()

  
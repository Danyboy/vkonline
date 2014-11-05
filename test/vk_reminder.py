# -*- coding: utf-8 -*-
import mechanize, threading, time, datetime, sqlite3, os

#личная учётка, куда будут приходить уведомления, и откуда будут отправлены уведомления другим люядм
name = 'username' #номер телефона или адрес почты, который вы используете для входа в вк
password = 'userpass'
self_username = 'usernick' #ник, который используется в ссылке на ваш профиль
#учётка для отправки уведомлений самому себе
fake_name = 'fake_username' #номер телефона или адрес почты фэйка
fake_pass = 'fake_userpass'

def get_rows(conn):
    c= conn.cursor()
    time_s = datetime.datetime.now().strftime('%H:%M')
    day_s = str(datetime.date.today())
    c.execute("SELECT * FROM reminder WHERE (date <= ? AND time <= ?)",(day_s, time_s))
    return c.fetchall()

def check_answers():
    conn = sqlite3.connect('reminder.db')
    rows = get_rows(conn)
    for row in rows:
        print row[5]
        c = conn.cursor()
        if row[3] == 'self':
            pass
            send_message(br_fake, get_self_page_id(br), row[4].encode('utf-8'))
        else:
            send_message(br, row[3], row[4].encode('utf-8'))
        if row[5] == '1' or row[5] == 1:
            c.execute("DELETE FROM reminder WHERE id = ?;", str(row[0]))
        else:
            time_s = (datetime.datetime.now()+datetime.timedelta(seconds=60)).strftime('%H:%M')
            num = int(row[5]) - 1
            c.execute("UPDATE reminder SET time = ?, times = ? WHERE id = ?",(time_s, str(num), row[0]))
        conn.commit()


def valid_time(time_text):
    try:
        datetime.datetime.strptime(time_text, '%H:%M')
        return True
    except ValueError:
        send_message(br_fake, get_self_page_id(br), 'неверный формат времени')
        return False

def valid_date(date_text):
    try:
        datetime.datetime.strptime(date_text, '%Y-%m-%d')
        return True
    except ValueError:
        send_message(br_fake, get_self_page_id(br), 'неверный формат даты')
        return False

def send_message(br, id, message):
    br.open('https://vk.com/im?sel='+id)
    br.select_form(nr=0)
    br.form['message'] = message
    br.submit()

def let_it_do(user, time_s, day_s, message, times):
    if valid_time(time_s) and valid_date(day_s):
        c = conn.cursor()
        c.execute("INSERT INTO reminder (time, date, user, message, times) VALUES (?,?,?,?,?)",(time_s, day_s, user, message, str(times)))
        conn.commit()
        

def reply_to_message(br, message):
    if message.find('напомнить') == -1:
        print 'nothing'
    else:
        print 'I obey, my lord'
        ms_words = message.split(' ')
        user = 'self'
        time_s = datetime.datetime.now().strftime('%H:%M')
        day_s = str(datetime.date.today())
        msg = 'something went wrong'
        times = message.split('|')
        if len(times) == 1:
            times = '1'
        else:
            times = int(times[1])
        if ms_words[1] == 'в':
            user = 'self'
            time_s = ms_words[2]
            msg = message.split('текст ')[1].split('|')[0]
        elif ms_words[1] == 'день':
            user = 'self'
            time_s = ms_words[4]
            day_s = ms_words[2]
            msg = message.split('текст ')[1].split('|')[0]
        elif ms_words[2] == 'в':
            user = get_page_id(br, ms_words[1])
            time_s = ms_words[3]
            msg = message.split('текст ')[1].split('|')[0]
        elif ms_words[2] == 'день':
            user = get_page_id(br, ms_words[1])
            time_s = ms_words[5]
            day_s = ms_words[3]
            msg = message.split('текст ')[1].split('|')[0]

        let_it_do(user, time_s, day_s, msg, times)


def get_message_text(response, number):
    return response.split('<a name="msg'+number)[1].split('class="mi_text">')[1].split('</div>')[0]

def play_with_messages(br, response):
    global first_start
    all_messages = response.split('class="messages bl_cont">')[1].split('<div id="mfoot"')[0].split('<a name="msg')
    all_numbers = []
    global msg_numbers
    for msg in all_messages:
        if msg != all_messages[0]:
            msg_num = msg.split('">')[0]
            all_numbers.append(msg_num)
    if first_start:
        msg_numbers = all_numbers
        first_start = False
    new_numbers = set(all_numbers) - set(all_numbers).intersection(set(msg_numbers))
    for num in new_numbers:
        reply_to_message(br, get_message_text(response, num))
    msg_numbers = all_numbers

def get_self_page_id(br):
    br.open('https://vk.com/'+self_username)
    return br.response().read().split('<form action="/wall')[1].split('?')[0]

def get_page_id(br, useranme):
    br.open('https://vk.com/'+useranme)
    return br.response().read().split('<a href="/photo')[1].split('_')[0]

def check_messages(br):
    br.open('https://vk.com/im?sel='+get_self_page_id(br))
    #print 'check'
    response = br.response().read()
    play_with_messages(br, response)
    check_answers(

msg_numbers = []
first_start = True
#подключаемся к базе данных с заданиями
if not os.path.isfile('reminder.db'):
    conn = sqlite3.connect('reminder.db')
    conn.execute('''CREATE TABLE  'reminder' (
    'id' INTEGER PRIMARY KEY AUTOINCREMENT,
    'time' TIME,
    'date' DATE,
    'user' VARCHAR( 100 ),
    'message' TEXT,
    'times' INTEGER
    )''')
    conn.text_factory = str
else:
    conn = sqlite3.connect('reminder.db')
    conn.text_factory = str
    print 'database has already been created'

#входим в фэйковый акаунт
br_fake = mechanize.Browser()
br_fake.set_handle_equiv(True)
br_fake.set_handle_redirect(True)
br_fake.set_handle_robots(False)
br_fake.open('https://vk.com/')

br_fake.select_form(nr=0)
br_fake.form['email'] = fake_name
br_fake.form['pass'] = fake_pass
br_fake.submit()

#входим в свой аккаунт
br = mechanize.Browser()
br.set_handle_equiv(True)
br.set_handle_redirect(True)
br.set_handle_robots(False)
br.open('https://vk.com/')

br.select_form(nr=0)
br.form['email'] = name
br.form['pass'] = password
br.submit()

#начинаем всё веселье
if br.response().read().find('mmi_mail') != -1:
    print 'INSIDE'
    while True:
        try:
            check_messages(br)
            time.sleep(5)
        except:
            pass
else:
    print 'FAIL'
# Work in TBC

# Get user ID (automatically fetched)
user_id = message.from_user.id

# Create web app URL with user ID parameter
web_app_url = f"https://xxxxxxx.com/?id={user_id}"

# Create inline keyboard with web app button
keyboard = [
    [{"text": "🚀 Start Now", "web_app": {"url": web_app_url}}]
]

# Send welcome message with web app button
bot.sendMessage(
    chat_id=message.chat.id,
    text=(
        "<b>👋 Welcome to the <u>Ultimate Ad-Watching Bot</u>!</b>\n\n"
        "💸 <i>Earn real rewards by watching short ads</i>\n"
        "⚡ <i>Instant credits, no waiting!</i>\n"
        "🎯 <b>Your time = Your money!</b>\n\n"
        "<u>🔥 Don't miss out on free earnings. Click below to start now!</u>\n\n"
        "<i>👨‍💻 Developed by</i> <b>@SPY_XXXX</b>"
    ),
    parse_mode="HTML",
    reply_markup={"inline_keyboard": keyboard}
)

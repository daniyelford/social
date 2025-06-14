from telethon import TelegramClient
from telethon.tl.types import ChannelParticipantsAdmins
from telethon.tl.functions.messages import GetHistoryRequest
import sys
import json
import os
api_id = YOUR_API_ID
api_hash = 'YOUR_API_HASH'
def get_channel_info(channel_username):
    client = TelegramClient('session_name', api_id, api_hash)
    with client:
        client.start()
        channel = client.get_entity(channel_username)
        participants = client.get_participants(channel)
        total_members = len(participants)
        admins = []
        for user in client.iter_participants(channel, filter=ChannelParticipantsAdmins()):
            admins.append({
                'id': user.id,
                'username': user.username,
                'first_name': user.first_name,
                'last_name': user.last_name,
                'is_self': user.is_self
            })
        photo_path = os.path.abspath(os.path.join(os.path.dirname(__file__), '../../public/images', f"{channel.username or channel.id}.jpg"))
        os.makedirs(os.path.dirname(photo_path), exist_ok=True)
        try:
            client.download_profile_photo(channel, file=photo_path)
        except Exception:
            photo_path = None
        history = client(GetHistoryRequest(
            peer=channel,
            limit=3,
            offset_date=None,
            offset_id=0,
            max_id=0,
            min_id=0,
            add_offset=0,
            hash=0
        ))
        views = []
        for msg in history.messages:
            if hasattr(msg, 'views') and msg.views is not None:
                views.append(msg.views)

        avg_views = sum(views) / len(views) if views else 0
        engagement_rate = (avg_views / total_members) * 100 if total_members > 0 else 0
        return {
            'title': channel.title,
            'username': channel.username,
            'id': channel.id,
            'participants_count': total_members,
            'about': getattr(channel, 'about', ''),
            'photo_path': f"/images/{channel.username or channel.id}.jpg" if os.path.exists(photo_path) else None,
            'admins': admins,
            'influencer_score': f"{engagement_rate:.2f}"
        }
if __name__ == "__main__":
    input_data = json.load(sys.stdin)
    channel_username = input_data.get('channel_username')
    try:
        info = get_channel_info(channel_username)
        print(json.dumps(info, ensure_ascii=False))
    except Exception as e:
        print(json.dumps({'error': str(e)}))
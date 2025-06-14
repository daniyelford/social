import instaloader
import sys
import json

def get_instagram_profile(username):
    loader = instaloader.Instaloader()
    try:
        profile = instaloader.Profile.from_username(loader.context, username)
        return {
            'username': profile.username,
            'full_name': profile.full_name,
            'followers': profile.followers,
            'bio': profile.biography,
            'profile_pic_url': profile.profile_pic_url
        }
    except Exception as e:
        return {'error': str(e)}

if __name__ == "__main__":
    input_data = json.load(sys.stdin)
    username = input_data.get("username")
    data = get_instagram_profile(username)
    print(json.dumps(data))
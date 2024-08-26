<?php

namespace App\Http\Controllers\actions;

use App\Events\SendInvitions;
use App\Http\Controllers\Controller;
use App\Models\Group;
use App\Models\Invition;
use App\Models\User;
use App\Models\User_Group;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use App\Helpers\validatoin;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Ramsey\Uuid\Type\Integer;
use Pusher\Pusher;

class GroupController extends Controller
{
    //
    use validatoin;
    public function createGroup(Request $request){
        try{
            $credentials = $this->validationProcess(['name'=>$request->name,'creator'=>$request->creator,'image'=>$request->image]);
            $validator = validator::make($request->all(),$credentials);

            if($validator->fails()){
                return response()->json([
                    'status'=>false,
                    'message'=>$validator->errors(),
                ],422 );
            }

//             image processing
            $photoName = Storage::disk('public')->putFile('groupsImage',$request->file('image'));
            $group = new Group();
            $group->name = $request->name;
            $group->creator_id = $request->creator;
            $group->image = $photoName;

            $group->save();

                User_Group::create([
                    'user_id' => $group->creator_id,
                    'group_id'=>$group->id,
                ]);
//
//            $members = explode(',',$request->members);
//            $members =$values = array_values($members);
//
//            $users = User::wherein('id',$members)->get();
//            $existingUserIds = $users->pluck('id')->toArray();
//            $nonExistingUserIds = array_diff($members, $existingUserIds);
//
//            foreach ()

//
            return response()->json([
                'status'=>true,
                'message'=>"The group has been created successfully"
            ],200);

        }catch (\Exception $e){
            return response()->json([
                'status'=>false,
                'message'=>'server error',
                'error'=>$e->getMessage(),
            ],500);
        }
    }

    public function inviteMemeber(Request $request , $id){

        try{
            $group = Group::where('id',$id)->first();
            if(!$group){
                return response()->json([
                    'status'=>false,
                    'message'=>'Group Not Found'
                ],404);
            }

            $users = User::wherein('id',$request->members)->get();
            $existingUserIds = $users->pluck('id')->toArray();
            $unExistingUsersIds= array_diff($request->members,$existingUserIds);
            if($unExistingUsersIds){
                return response()->json([
                    'status'=>false,
                    'message'=>"users don't exist",
                    'users' =>array_values($unExistingUsersIds)
                ],401);
            }

            $pusher = new Pusher(env('PUSHER_APP_KEY'), env('PUSHER_APP_SECRET'), env("PUSHER_APP_ID"), ['cluster'=>'eu']);

            foreach ($existingUserIds as $existingUserId) {
                $is_in_group = User_Group::where('group_id',$id)->where('user_id',$existingUserId)->exists();
                if($is_in_group){
//                    return response()->json([
//                        'status' => true,
//                        'message' => "this user is already in the group"
//                    ],200);
                }
                $invitation = new Invition();
                $user_name = User::where('id',$request->sender_id)->pluck('name');
                $group_name = Group::where('id',$id)->pluck('name');
                $invitation->message = $user_name[0] . " invited you to join group ". $group_name[0];
                $invitation->sender_id = $request->sender_id;
                $invitation->receiver_id= $existingUserId;
                $invitation->group_id = $id;
                $invitation->save();
//                Event::dispatch(new SendInvitions($invitation->message,$existingUserId));
                $channel = 'my-channel'.$existingUserId;
                $pusher->trigger($channel, 'my-event', ['message' => $invitation->message]);
            }

            return response()->json([
                'status' => true,
                'message' => "users have been invited successfully"
            ],200);

        }catch (\Exception $e){
            return response()->json([
                'status'=>false,
                'message'=>'server error',
                'error'=>$e->getMessage(),
            ],500);
        }
    }

    public function UserInvitation(int $id){
        try{
            $invitations = Invition::where('receiver_id',$id)->where('confirmation',0)->get();
            return response()->json([
                'status' => true,
                'invitations' => $invitations->toArray()
            ],200);
        }catch (\Exception $e){
            return response()->json([
                'status'=>false,
                'message'=>'server error',
                'error'=>$e->getMessage(),
            ],500);
        }
    }

    public function getInvitation(int $id,int $notification_id){
        try{
            $invitation = Invition::where('id',$notification_id)->where('receiver_id',$id)->get();
            return response()->json([
                'status' => true,
                'invitations' => $invitation->toArray()
            ],200);
        }catch (\Exception $e){
            return response()->json([
                'status'=>false,
                'message'=>'server error',
                'error'=>$e->getMessage(),
            ],500);
        }
    }

    public function confirmInvitation(int $id, int $notification_id){
        try{

            $group_id = Invition::where('id',$notification_id)->pluck('group_id');
            $is_confirmed = Invition::where('id',$notification_id)->update([
                'confirmation' => 1
            ]);
            if($is_confirmed){
                User_Group::create([
                   'user_id' => $id,
                   'group_id'=>$group_id[0]
                ]);
                return response()->json([
                    'status' => true,
                    'message' => 'You are now a member in the'.$group_id
                ],200);
            }

        }catch (QueryException $e){
            return response()->json([
                'status'=>false,
                'message'=>'you are already in this group ',
            ],200);
        }

        catch (\Exception $e){
            return response()->json([
                'status'=>false,
                'message'=>'server error',
                'error'=>$e->getMessage(),
            ],500);
        }
    }

    public function getGroups(int $id){
        try{
            $groups = User_Group::where('user_id',$id)->pluck('group_id');
            return response()->json([
                'status'=>true,
                'groups'=>$groups,
            ],200);
        }catch (\Exception $e){
            return response()->json([
                'status'=>false,
                'message'=>'server error',
                'error'=>$e->getMessage(),
            ],500);
        }
    }
}

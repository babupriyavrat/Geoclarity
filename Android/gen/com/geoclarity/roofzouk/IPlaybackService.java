/*
 * This file is auto-generated.  DO NOT MODIFY.
 * Original file: C:\\Users\\Babu\\Desktop\\geoclarity_2\\Android\\src\\com\\geoclarity\\roofzouk\\IPlaybackService.aidl
 */
package com.geoclarity.roofzouk;
public interface IPlaybackService extends android.os.IInterface
{
/** Local-side IPC implementation stub class. */
public static abstract class Stub extends android.os.Binder implements com.geoclarity.roofzouk.IPlaybackService
{
private static final java.lang.String DESCRIPTOR = "com.geoclarity.roofzouk.IPlaybackService";
/** Construct the stub at attach it to the interface. */
public Stub()
{
this.attachInterface(this, DESCRIPTOR);
}
/**
 * Cast an IBinder object into an com.geoclarity.roofzouk.IPlaybackService interface,
 * generating a proxy if needed.
 */
public static com.geoclarity.roofzouk.IPlaybackService asInterface(android.os.IBinder obj)
{
if ((obj==null)) {
return null;
}
android.os.IInterface iin = obj.queryLocalInterface(DESCRIPTOR);
if (((iin!=null)&&(iin instanceof com.geoclarity.roofzouk.IPlaybackService))) {
return ((com.geoclarity.roofzouk.IPlaybackService)iin);
}
return new com.geoclarity.roofzouk.IPlaybackService.Stub.Proxy(obj);
}
@Override public android.os.IBinder asBinder()
{
return this;
}
@Override public boolean onTransact(int code, android.os.Parcel data, android.os.Parcel reply, int flags) throws android.os.RemoteException
{
switch (code)
{
case INTERFACE_TRANSACTION:
{
reply.writeString(DESCRIPTOR);
return true;
}
}
return super.onTransact(code, data, reply, flags);
}
private static class Proxy implements com.geoclarity.roofzouk.IPlaybackService
{
private android.os.IBinder mRemote;
Proxy(android.os.IBinder remote)
{
mRemote = remote;
}
@Override public android.os.IBinder asBinder()
{
return mRemote;
}
public java.lang.String getInterfaceDescriptor()
{
return DESCRIPTOR;
}
}
}
}

APPLICATION_NAME=creativestreams
KUBE_POD_NAME=$(kubectl get po | grep $APPLICATION_NAME | cut -d' ' -f1)
COMMAND=${1}
kubectl exec -it $KUBE_POD_NAME -c app -- $COMMAND

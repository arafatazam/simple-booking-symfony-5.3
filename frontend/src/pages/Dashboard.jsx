import React, { useState, useRef } from "react";
import {
  Calendar,
  Modal,
  Spin,
  Typography,
  Form,
  Button,
  InputNumber,
  notification,
} from "antd";

const { Text } = Typography;

export default function Dashboard() {
  const [isSpinning, setSpinning] = useState(false);
  const [bookingData, setBookingData] = useState({});
  const [form] = Form.useForm();
  const selecTed = useRef(null);

  const [modalVisible, setModalVisible] = useState(false);
  const [modalBusy, setModalBusy] = useState(false);

  const onPanelChange = (date, mode) => {
    if (mode == "year") {
      return;
    }
    setSpinning(true);
    setTimeout(() => {
      setBookingData({ "2021-07-15": { available: 12, booked: 10 } });
      setSpinning(false);
    }, 1500);
  };

  const renderDateCell = (date) => {
    let key = date.format("YYYY-MM-DD");
    if (!bookingData[key]) {
      return <Text type="secondary">Click To Create Vacancy</Text>;
    }
    return (
      <>
        <p>
          <Text>Availabe - {bookingData[key]["available"]}</Text>
        </p>
        <p>
          <Text type="warning" mark>
            Booked - {bookingData[key]["booked"]}
          </Text>
        </p>
      </>
    );
  };

  const showModal = (date) => {
    form.resetFields();
    form.setFieldsValue({
      slotsAvailable: 12,
      price: 10.5,
    });
    setModalVisible(true);
  };

  const onDateChange = (date) => {
    let prev = selecTed.current;
    selecTed.current = date;
    if (
      prev != null &&
      (prev.year() != date.year() || prev.month() != date.month())
    ) {
      return;
    }
    showModal(date);
  };
  return (
    <>
      <Spin spinning={isSpinning} size="large">
        <Calendar
          onPanelChange={onPanelChange}
          onSelect={onDateChange}
          dateCellRender={renderDateCell}
        ></Calendar>
      </Spin>
      <Modal
        visible={modalVisible}
        footer={null}
        onCancel={() => setModalVisible(false)}
      >
        <Spin spinning={modalBusy}>
          <Form
            form={form}
            style={{ marginTop: "30px" }}
            onFinishFailed={(errorInfo) =>
              notification.open({ type: "error", message: errorInfo })
            }
            onFinish={(values) => {
              console.log(values);
              setModalVisible(false);
            }}
          >
            <Form.Item
              name="slotsAvailable"
              rules={[{ required: true, type: "integer", min: 1 }]}
              label="Available Slots"
            >
              <InputNumber min={1} placeholder="Input number of slots" />
            </Form.Item>
            <Form.Item
              name="price"
              rules={[{ required: true, type: "float", min: 0 }]}
              label="Price"
            >
              <InputNumber placeholder="Input price" />
            </Form.Item>
            <Form.Item>
              <Button type="primary" htmlType="submit">Submit</Button>
            </Form.Item>
          </Form>
        </Spin>
      </Modal>
    </>
  );
}
